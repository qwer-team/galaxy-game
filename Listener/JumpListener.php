<?php

namespace Galaxy\GameBundle\Listener;

use Galaxy\GameBundle\Event\JumpEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Galaxy\GameBundle\Entity\UserInfo;
use Galaxy\GameBundle\Entity\Jump;
use Galaxy\GameBundle\Entity\UserLog;
use Galaxy\GameBundle\Entity\Basket;

class JumpListener extends ContainerAware
{

    public function onEvent(JumpEvent $event)
    {
        $jump = $event->getJump();

        $userId = $jump->getUserId();
        $userInfo = $this->getUserInfo($userId);

        $userInfo->setNewCoordinates($jump);
        if ($jump->getSuperjump()) {
            $userInfo->subSuperJump();
        }
        $userInfo->addTotalJump();


        $em = $this->getEntityManager();


        $em->getConnection()->beginTransaction();

        try {
            $em->flush();
            $this->debitFunds($userInfo);
            $response = $this->spaceJump($jump);
            $pointTag = $response["type"]["tag"];
            $this->logMessage($userId, $response["type"]["message1"]);
            $this->cleanCapturedPrizes($userInfo);
            $this->updateBoughtPrizes($userInfo);
            $this->processMessage($userInfo);
            $this->processQuestions($userInfo);
            $this->processTypeJump($pointTag, $response, $userId);
            $this->processPrizeJump($response, $jump, $userInfo);
            $this->processTransfer($userInfo);
            $event->setResponse($response);
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }
    }

    private function logMessage($userId, $message)
    {
        if ($message == "") {
            return;
        }

        $logEntry = new UserLog();
        $logEntry->setUserId($userId);
        $logEntry->setText($message);

        $em = $this->getEntityManager();
        $em->persist($logEntry);
        $em->flush();
    }

    /**
     * 
     * @param type $userId
     * @return \Galaxy\GameBundle\Entity\UserInfo
     */
    private function getUserInfo($userId)
    {
        $em = $this->getEntityManager();
        $namespace = "GalaxyGameBundle:UserInfo";
        $repo = $em->getRepository($namespace);

        return $repo->findOneByUserId($userId);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $em = $this->container->get("doctrine.orm.entity_manager");
    }

    private function debitFunds(UserInfo $userInfo)
    {
        $flipper = $userInfo->getFlipper();
        $data = array(
            'OA1' => $userInfo->getUserId(),
            'summa1' => $flipper->getCostJump(),
            'account' => ($flipper->getPaymentFromDeposit() ? 2 : 1)
        );
        $url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($url, $data));

        return $response;
    }

    private function spaceJump(Jump $jump)
    {
        $rawUrl = $this->container->getParameter("space.jump_proceed.url");
        $find = array("{x}", "{y}", "{z}");
        $replace = $jump->getCoordinates();

        $url = str_replace($find, $replace, $rawUrl);
        $result = json_decode($this->makeRequest($url), true);

        return $result;
    }

    private function processMessage(UserInfo $userInfo)
    {
        $message = $userInfo->getMessage();
        if ($message) {
            $userInfo->setMessage(null);
            $this->getEntityManager()->remove($message);
        }
    }

    private function processQuestions(UserInfo $userInfo)
    {
        $questions = $userInfo->getQuestions();

        foreach ($questions as $question) {
            if ($question->getStatus() == 1) {
                $question->subJumpsToQuestion();
                if ($question->getJumpsToQuestion() == 0) {
                    $question->setStatus(2);
                }
            }
        }
        $this->getEntityManager()->flush();
    }

    private function processTypeJump($tag, $response, $userId)
    {
        $serviceName = "game.process_point_type.$tag";
        if ($this->container->has($serviceName)) {
            $service = $this->container->get($serviceName);
            $service->proceed($response, $userId);
        }
    }

    private function cleanCapturedPrizes($userInfo)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository("GalaxyGameBundle:Basket");
        $criteria = array(
            "userInfo" => $userInfo,
            "bought" => false,
        );

        $oldPrize = $repo->findOneBy($criteria);
        if (!$oldPrize) {
            return;
        }
        if ($oldPrize->getRestore()) {
            $this->restorePrize($oldPrize);
        }
        $em->remove($oldPrize);
        $em->flush();
    }

    private function updateBoughtPrizes($userInfo)
    {
        $em = $this->getEntityManager();
        $repo = $em->getRepository("GalaxyGameBundle:Basket");
        $spaceService = $this->container->get("space.remote_service");
        $criteria = array(
            "userInfo" => $userInfo,
            "bought" => true,
        );

        $prizes = $repo->findBy($criteria);
        if (!count($prizes)) {
            return;
        }

        foreach ($prizes as $prize) {
            $prize->subJumpsRemain();
            if ($prize->getJumpsRemain() == 0) {
                if ($prize->getRestore()) {
                    $spaceService->restorePrize($prize);
                }
                $em->remove($prize);
            }
        }
        $em->flush();
    }

    private function processPrizeJump($response, Jump $jump, UserInfo $userInfo)
    {
        if ($response['subelement'] == null || $response['element']['blocked']) {
            return;
        }

        $subelement = $response['subelement'];
        $element = $response['element'];
        $basket = new Basket();
        $basket->setUserInfo($userInfo);
        $basket->setElementId($element['id']);
        $basket->setJumpsRemain($element['available']);
        $basket->setSubelementId($subelement['id']);
        $basket->setRestore(!$subelement['restore']);
        $basket->setCoordinates($jump->getCoordinates());

        $em = $this->getEntityManager();
        $em->persist($basket);

        $em->flush();
    }

    private function processTransfer(UserInfo $userInfo)
    {
        $userId = $userInfo->getUserId();
        $fundInfo = $this->getFundsInfo($userId);
        $transActive = $fundInfo->transActive;
        $transSafe = $fundInfo->transSafe;
        if ($transActive > 0) {
            $debitResponse = $this->debitTransferFunds($userId, $transActive, 4);
            if ($debitResponse->result == 'success') {
                $this->transTransferFunds($userId, $transActive, 1);
            }
        }
        if ($transSafe > 0) {
            $debitResponse = $this->debitTransferFunds($userId, $transSafe, 5);
            if ($debitResponse->result == 'success') {
                $this->transTransferFunds($userId, $transSafe, 2);
            }
        }
    }
    
    private function getFundsInfo($userId)
    {
        $rawUrl = $this->container->getParameter("documents.get_funds.url");
        $url = str_replace("{userId}", $userId, $rawUrl);
        $fundInfo = json_decode($this->makeRequest($url));
        return $fundInfo;
    }

    private function debitTransferFunds($userId, $summa, $account)
    {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($url, $data));
        return $response;
    }

    private function transTransferFunds($userId, $summa, $account)
    {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.trans_funds.url");

        $response = json_decode($this->makeRequest($url, $data));
        return $response;
    }

    private function makeRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (!is_null($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

}