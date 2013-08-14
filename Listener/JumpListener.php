<?php

namespace Galaxy\GameBundle\Listener;

use Galaxy\GameBundle\Event\JumpEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Galaxy\GameBundle\Entity\UserInfo;
use Galaxy\GameBundle\Entity\Jump;
use Galaxy\GameBundle\Entity\UserLog;
use Galaxy\GameBundle\Entity\Basket;
use Qwer\Curl\Curl;

class JumpListener extends ContainerAware {

    public function onEvent(JumpEvent $event) {
        $jump = $event->getJump();

        $userId = $jump->getUserId();
        $userInfo = $this->getUserInfo($userId);


        if ($jump->getSuperjump()) {
            $userInfo->subSuperJump();
        }

        $flipper = $userInfo->getFlipper();
        $jumpAcc = $flipper->getPaymentFromDeposit() ? 2 : 1;
        $em = $this->getEntityManager();


        $em->getConnection()->beginTransaction();

        try {
            $this->processRentFlipper($userInfo);
            $this->debitFunds($userId, $flipper->getCostJump(), $jumpAcc);
            $this->processTransfer($userInfo);
            $this->cleanCapturedPrizes($userInfo);
            $this->processMoveZone($userInfo, $jump);
            $response = $this->spaceJump($jump);
            $pointTag = $response["type"]["tag"];
            $parameter = $response['subtype']['parameter'];
            $this->logMessage($userId, $response["type"]["message1"]);

            $this->updateBoughtPrizes($userInfo);
            $this->processMessage($userInfo);
            $this->processQuestions($userInfo);
            $this->processTypeJump($pointTag, $parameter, $userId);
            $this->processPrizeJump($response, $jump, $userInfo);
            $userInfo->setNewCoordinates($jump);
            $userInfo->addTotalJump();
            $em->flush();
            $event->setResponse($response);
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }
    }

    private function logMessage($userId, $message) {
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
    private function getUserInfo($userId) {
        $em = $this->getEntityManager();
        $namespace = "GalaxyGameBundle:UserInfo";
        $repo = $em->getRepository($namespace);

        return $repo->findOneByUserId($userId);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager() {
        return $em = $this->container->get("doctrine.orm.entity_manager");
    }

    private function spaceJump(Jump $jump) {
        $rawUrl = $this->container->getParameter("space.jump_proceed.url");
        $find = array("{x}", "{y}", "{z}");
        $replace = $jump->getCoordinates();

        $url = str_replace($find, $replace, $rawUrl);
        $result = json_decode($this->makeRequest($url), true);

        return $result;
    }

    private function processMessage(UserInfo $userInfo) {
        $message = $userInfo->getMessage();
        if ($message) {
            $userInfo->setMessage(null);
            $this->getEntityManager()->remove($message);
        }
    }

    private function processQuestions(UserInfo $userInfo) {
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

    private function processTypeJump($tag, $parameter, $userId) {
        $serviceName = "game.process_point_type.$tag";
        if ($this->container->has($serviceName)) {
            $service = $this->container->get($serviceName);
            $service->proceed($parameter, $userId);
        }
    }

    private function cleanCapturedPrizes($userInfo) {
        $em = $this->getEntityManager();
        $repo = $em->getRepository("GalaxyGameBundle:Basket");
        $spaceService = $this->container->get("space.remote_service");
        $criteria = array(
            "userInfo" => $userInfo,
            "bought" => false,
        );

        $oldPrize = $repo->findOneBy($criteria);
        if (!$oldPrize) {
            return;
        }
        if ($oldPrize->getRestore()) {
            $spaceService->restorePrize($oldPrize);
        }
        $em->remove($oldPrize);
        $em->flush();
    }

    private function updateBoughtPrizes($userInfo) {
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

    private function processPrizeJump($response, Jump $jump, UserInfo $userInfo) {
        if ($response['subelement'] == null || $response['element']['blocked']) {
            return;
        }

        $subelement = $response['subelement'];
        $element = $response['element'];
        $basket = new Basket();
        $basket->setUserInfo($userInfo);
        $basket->setElementId($element['id']);
        $basket->setPrizeId($element['prizeId']);
        $basket->setJumpsRemain($element['available']);
        $basket->setSubelementId($subelement['id']);
        $basket->setRestore(!$subelement['restore']);
        $basket->setPrizeLength($response['prizeLen']);
        $basket->setCoordinates($jump->getCoordinates());

        $em = $this->getEntityManager();
        $em->persist($basket);

        $em->flush();
    }

    private function processTransfer(UserInfo $userInfo) {
        $userId = $userInfo->getUserId();
        $fundInfo = $this->getFundsInfo($userId);
        $transActive = $fundInfo->transActive;
        $transSafe = $fundInfo->transSafe;
        if ($transActive > 0) {
            $debitResponse = $this->debitFunds($userId, $transActive, 4);
            if ($debitResponse->result == 'success') {
                $this->transFunds($userId, $transActive, 1);
            }
        }
        if ($transSafe > 0) {
            $debitResponse = $this->debitFunds($userId, $transSafe, 5);
            if ($debitResponse->result == 'success') {
                $this->transFunds($userId, $transSafe, 2);
            }
        }
    }

    private function processRentFlipper(UserInfo $userInfo) {
        $flipper = $userInfo->getFlipper();
        if ($flipper->getId() == 1) {
            return;
        } elseif ($userInfo->getCountRentJumps() == 0) {
            $this->debitFunds($userInfo->getUserId(), $flipper->getRentCost(), 1);
            $userInfo->setCountRentJumps($flipper->getRentDuration());
        }
        $userInfo->decCountRentJumps();

        $em = $this->getEntityManager();
        $em->flush();
    }

    private function processMoveZone(UserInfo $userInfo, Jump $jump) {

        if ($userInfo->getZoneJumps() == 0) {
            $this->clearZone($userInfo);
            return;
        } elseif (!$this->pointInMoveZone($userInfo, $jump)) {
            return;
        }


        $data = array(
            '1' => "plus_percent",
            '2' => "plus_all_period",
            '3' => "plus_prize_period"
        );
        $userId = $userInfo->getUserId();
        $userInfo->decZoneJump();
        $pointCoord = $jump->getCoordinates();
        $pointJumpId = $this->getId($pointCoord[0], $pointCoord[1], $pointCoord[2]);

        if ($pointJumpId == $userInfo->getPointId()) {

            if ($userInfo->getPointType() != 4) {
                $point = $this->getSubtype($userInfo->getSubTypeId());
                $this->processTypeJump($data[$userInfo->getPointType()], $point["parameter"], $userId);
            } else {
                $response = $this->getSubelement($userInfo->getSubElementId());
                $this->processPrizeJump($response, $jump, $userInfo);
            }
            $this->clearZone($userInfo);
        }
    }

    private function clearZone(UserInfo $userInfo) {
        $userInfo->setMinRadius(0);
        $userInfo->setMaxRadius(0);
        $userInfo->setPointId(0);
        $userInfo->setSubElementId(0);
        $userInfo->setZoneJumps(0);
        $userInfo->setCentralPointId(0);
        $userInfo->setPointType(0);
        $userInfo->setSubTypeId(0);
        $em = $this->getEntityManager();

        $em->flush();
    }

    private function getId($x, $y, $z) {
        $id = $x + ($y - 1) * 1000 + ($z - 1) * 1000000;

        return $id;
    }

    private function getCoords($id) {
        $id--;
        $x = $id % 1000;
        $id -= $x;
        $x++;

        $y = $id % 1000000 / 1000;
        $id -= $y * 1000;
        $y++;
        $z = $id % 1000000000 / 1000000;
        $z++;

        return array(
            "x" => $x,
            "y" => $y,
            "z" => $z,
        );
    }

    private function pointInMoveZone(UserInfo $userInfo, Jump $jump) {

        $minR = $userInfo->getMinRadius();
        $maxR = $userInfo->getMaxRadius();

        $centralCoord = $this->getCoords($userInfo->getCentralPointId());
        $pointCoord = $jump->getCoordinates();

        $dx = $centralCoord['x'] - $pointCoord[0];
        $dy = $centralCoord['y'] - $pointCoord[1];
        $dz = $centralCoord['z'] - $pointCoord[2];

        $distance1 = sqrt(pow($dx, 2) + pow($dy, 2) + pow($dz, 2));

        $distance2 = sqrt(pow(999 - abs($dx), 2) + pow($dy, 2) + pow($dz, 2));
        $distance3 = sqrt(pow($dx, 2) + pow(999 - abs($dy), 2) + pow($dz, 2));
        $distance4 = sqrt(pow($dx, 2) + pow($dy, 2) + pow(999 - abs($dz), 2));

        $distance5 = sqrt(pow(999 - abs($dx), 2) + pow(999 - abs($dy), 2) + pow($dz, 2));
        $distance6 = sqrt(pow(999 - abs($dx), 2) + pow($dy, 2) + pow(999 - abs($dz), 2));
        $distance7 = sqrt(pow($dx, 2) + pow(999 - abs($dy), 2) + pow(999 - abs($dz), 2));

        $distance8 = sqrt(pow(999 - abs($dx), 2) + pow(999 - abs($dy), 2) + pow(999 - abs($dz), 2));

        $distance = min($distance1, $distance2, $distance3, $distance4, $distance5, $distance6, $distance7, $distance8);

        if ($minR <= $distance && $distance <= $maxR) {

            return true;
        }
        return false;
    }

    private function getFundsInfo($userId) {
        $rawUrl = $this->container->getParameter("documents.get_funds.url");
        $url = str_replace("{userId}", $userId, $rawUrl);
        $fundInfo = json_decode($this->makeRequest($url));
        return $fundInfo;
    }

    private function debitFunds($userId, $summa, $account) {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($url, $data));
        return $response;
    }

    private function transFunds($userId, $summa, $account) {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.trans_funds.url");

        $response = json_decode($this->makeRequest($url, $data));
        return $response;
    }

    private function getSubelement($id) {
        $rawUrl = $this->container->getParameter("space.get_subelement.url");
        $url = str_replace("{id}", $id, $rawUrl);
        return json_decode($this->makeRequest($url), true);
    }

    private function getSubtype($id) {
        $rawUrl = $this->container->getParameter("space.get_subtype.url");
        $url = str_replace("{id}", $id, $rawUrl);
        return json_decode($this->makeRequest($url), true);
    }

    private function makeRequest($url, $data = null) {
        return Curl::makeRequest($url, $data);
    }

}