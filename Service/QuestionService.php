<?php

namespace Galaxy\GameBundle\Service;

use Galaxy\GameBundle\Entity\Question;
use Galaxy\GameBundle\Entity\UserInfo;
use Doctrine\ORM\EntityManager;
use Galaxy\GameBundle\Service\InfoRemoteService;
use Galaxy\GameBundle\Entity\UserLog;

class QuestionService
{

    /**
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     * @var \Galaxy\GameBundle\Service\InfoRemoteService 
     */
    private $infoRemote;

    /**
     *
     * @var \Galaxy\GameBundle\Service\FrontendRemoteService 
     */
    private $frontendRemote;

    /**
     * @var \Galaxy\GameBundle\Service\DocumentsRemoteService 
     */
    private $documentsRemote;
    
    /**
     * @var \Galaxy\GameBundle\Service\PointProcess\Black 
     */
    private $blackPointProcess;

    public function success(Question $question)
    {
        list($messageInfo, $fundsInfo) = $this->getInfoAndResetQuestion($question, true);

        $userInfo = $question->getUserInfo();
        $this->incPoints($userInfo, $messageInfo, $fundsInfo);
        $this->incMinimum($userInfo, $messageInfo);
        $this->incMinimumForPrize($userInfo, $messageInfo);
        $this->incAll($userInfo, $messageInfo);
        $this->incFlipper($userInfo, $messageInfo);
        $this->incSuperjump($userInfo, $messageInfo);

        $this->em->flush();
        $this->em->clear();
    }

    public function fail(Question $question)
    {
        list($messageInfo, $fundsInfo) = $this->getInfoAndResetQuestion($question, false);

        $userInfo = $question->getUserInfo();
        $this->decPoints($userInfo, $messageInfo, $fundsInfo);
        $this->decSuperjump($userInfo, $messageInfo);
        $this->decFlipper($userInfo, $messageInfo);
        $this->nullPoints($userInfo, $messageInfo, $fundsInfo);
        $this->firstFlipper($userInfo, $messageInfo);
        $this->delMaxgroup($userInfo, $messageInfo);
        $this->blackPoint($userInfo, $messageInfo);

        $this->em->flush();
        $this->em->clear();
    }

    private function blackPoint(UserInfo $userInfo, $messageInfo)
    {
        if($messageInfo->blackPointActv) {
            $response = array();
            $response['subtype'] = array();
            $response['subtype']['parameter'] = 600;
            
            $userId = $userInfo->getId();
            $this->blackPointProcess->proceed($response, $userId);
            $message = $messageInfo->blackPointMess;
            $this->logMessage($userId, $message);
        }
    }

    private function delMaxgroup(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->delElemGroupActv) {

            $prizes = array();
            foreach ($userInfo->getBasket() as $element) {
                $prizeLen = $element->getPrizeLength();
                $prizeId = $element->getPrizeId();
                if (!isset($prizes[$prizeId])) {
                    $prizes[$prizeId] = array();
                    $prizes[$prizeId]['len'] = $prizeLen;
                    $prizes[$prizeId]['elements'] = array();
                    $prizes[$prizeId]['has'] = 0;
                }
                $prizes[$prizeId]['elements'][] = $element;
                $prizes[$prizeId]['has']++;
            }

            $percent = 0;
            $id = null;
            foreach ($prizes as $prizeId => $prize) {
                $prizePercent = $prize['has'] / $prize['len'];
                if ($prizePercent > $percent) {
                    $percent = $prizePercent;
                    $id = $prizeId;
                }
            }

            foreach ($prizes[$id]['elements'] as $element) {
                $this->em->remove($element);
            }

            $userId = $userInfo->getId();
            $message = $messageInfo->delElemGroupMess;
            $this->logMessage($userId, $message);
        }
    }

    private function firstFlipper(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->firstFlipperActv) {
            $flipperId = $userInfo->getFlipper();
            if ($flipperId == 1) {
                return;
            }
            $flipper = $this->getFlipper(1);
            $userInfo->setFlipper($flipper);

            $userId = $userInfo->getId();
            $message = $messageInfo->firstFlippertMess;
            $this->logMessage($userId, $message);
        }
    }

    private function nullPoints(UserInfo $userInfo, $messageInfo, $fundsInfo)
    {
        if ($messageInfo->activeCancelActv) {
            $sub = $fundsInfo->active;

            $userId = $userInfo->getId();
            $this->documentsRemote->debitFunds($userId, $sub, 1);
            $message = $messageInfo->activeCancelMess;
            $this->logMessage($userId, $message);
        }
    }

    private function decFlipper(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->decFlipAmountActv) {
            $flipperId = $userInfo->getFlipper();
            if ($flipperId == 1) {
                return;
            }
            $newFlipperId = $flipperId + $messageInfo->decFlipAmount;

            if ($newFlipperId < 1) {
                $newFlipperId = 1;
            }

            $flipper = $this->getFlipper($newFlipperId);
            $userInfo->setFlipper($flipper);

            $userId = $userInfo->getId();
            $message = $messageInfo->decFlipAmountMess;
            $this->logMessage($userId, $message);
        }
    }

    private function decSuperjump(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->superjumpCancelActv) {
            $userInfo->setSuperJumps(0);

            $userId = $userInfo->getId();
            $message = $messageInfo->superjumpCancelMess;
            $this->logMessage($userId, $message);
        }
    }

    private function decPoints(UserInfo $userInfo, $messageInfo, $fundsInfo)
    {
        if ($messageInfo->decPointsActv) {
            $sub = 0;
            if ($messageInfo->decPointsProc) {
                $mult = ($messageInfo->decPoints / 100);
                $sub = floor($fundsInfo->active * $mult);
            } else {
                $sub = $messageInfo->decPoints;
            }

            $userId = $userInfo->getId();
            $this->documentsRemote->debitFunds($userId, $sub, 1);
            $message = $messageInfo->decPointsMess;
            $this->logMessage($userId, $message);
        }
    }

    private function incSuperjump(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->superjumpAmountActv) {
            $super = $userInfo->getSuperJumps();
            $super += $messageInfo->superjumpAmount;
            $userInfo->setSuperJumps($super);

            $userId = $userInfo->getId();
            $message = $messageInfo->superjumpAmountMess;
            $this->logMessage($userId, $message);
        }
    }

    private function incFlipper(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->incFlipAmountActv) {
            $flipperId = $userInfo->getFlipper();
            if ($flipperId == 5) {
                return;
            }
            $newFlipperId = $flipperId + $messageInfo->incFlipAmount;

            if ($newFlipperId > 5) {
                $newFlipperId = 5;
            }

            $flipper = $this->getFlipper($newFlipperId);
            $userInfo->setFlipper($flipper);

            $userId = $userInfo->getId();
            $message = $messageInfo->incFlipAmountMess;
            $this->logMessage($userId, $message);
        }
    }

    private function getFlipper($id)
    {
        $repo = $this->em->getRepository("GalaxyGameBundle:Flipper");

        $flipper = $repo->find($id);
        return $flipper;
    }

    private function incMinimum(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->incOwnElemActv) {
            $basket = $userInfo->getBasket();

            $minimalVal = 1000;
            $minimal = array();

            foreach ($basket as $element) {
                $jumps = $element->getJumpsRemain();
                if ($jumps < $minimalVal) {
                    $minimal = array();
                    $minimal[] = $element;
                    $minimalVal = $jumps;
                } elseif ($jumps == $minimalVal) {
                    $minimal[] = $element;
                }
            }

            foreach ($minimal as $element) {
                $jumps = $element->getJumpsRemain();
                $jumps += $messageInfo->incOwnElem;
                $element->setJumpsRemain($jumps);
            }

            $userId = $userInfo->getId();
            $message = $messageInfo->incOwnElemMess;
            $this->logMessage($userId, $message);
        }
    }

    private function incMinimumForPrize(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->incDurationMinElemActv) {
            $basket = $userInfo->getBasket();

            $minimalVal = array();
            $minimal = array();

            foreach ($basket as $element) {
                $jumps = $element->getJumpsRemain();
                $prizeId = $element->getPrizeId();
                if (!isset($minimalVal[$prizeId])) {
                    $minimalVal[$prizeId] = 1000;
                }
                $miniVal = $minimalVal[$prizeId];
                if ($jumps < $miniVal) {
                    $minimal[$prizeId] = array();
                    $minimal[$prizeId][] = $element;
                    $minimalVal[$prizeId] = $jumps;
                } elseif ($jumps == $miniVal) {
                    $minimal[$prizeId][] = $element;
                }
            }

            foreach ($minimal as $prize) {
                foreach ($prize as $element) {
                    $jumps = $element->getJumpsRemain();
                    $jumps += $messageInfo->incDurationMinElem;
                    $element->setJumpsRemain($jumps);
                }
            }

            $userId = $userInfo->getId();
            $message = $messageInfo->incDurationMinElemMess;
            $this->logMessage($userId, $message);
        }
    }

    private function incAll(UserInfo $userInfo, $messageInfo)
    {
        if ($messageInfo->incDurationAllElemActv) {
            $basket = $userInfo->getBasket();

            foreach ($basket as $element) {
                $jumps = $element->getJumpsRemain();
                $jumps += $messageInfo->incDurationAllElem;
                $element->setJumpsRemain($jumps);
            }

            $userId = $userInfo->getId();
            $message = $messageInfo->incDurationAllElemMess;
            $this->logMessage($userId, $message);
        }
    }

    private function incPoints(UserInfo $userInfo, $messageInfo, $fundsInfo)
    {
        if ($messageInfo->incPointsActv) {
            $add = 0;
            if (isset($messageInfo->incPointsProc) && $messageInfo->incPointsProc) {
                $mult = ($messageInfo->incPoints / 100);
                $add = floor($fundsInfo->active * $mult);
            } else {
                $add = $messageInfo->incPoints;
            }

            $userId = $userInfo->getId();
            $this->documentsRemote->depositeFunds($userId, $add, 1);
            $message = $messageInfo->incPointsMess;
            $this->logMessage($userId, $message);
        }
    }

    private function getInfoAndResetQuestion(Question $question, $result)
    {
        $question = $this->blockQuestion($question);
        $messageId = $question->getMessageId();
        $this->frontendRemote->resetQuestion($question->getId(), $result);
        $messageInfo = $this->infoRemote->getQuestion($messageId);
        $userId = $question->getUserInfo()->getId();
        $fundsInfo = $this->documentsRemote->getFunds($userId);

        return array($messageInfo, $fundsInfo);
    }

    private function blockQuestion(Question $question)
    {
        $id = $question->getId();
        $this->em->detach($question);
        $repo = $this->em->getRepository("GalaxyGameBundle:Question");
        $question = $repo->find($id);
        if ($question->getStatus() == 4) {
            throw new \Exception('message is blocked');
        }
        $question->setStatus(4);
        $this->em->flush();

        return $question;
    }

    private function logMessage($userId, $message)
    {
        if ($message == "") {
            return;
        }

        $logEntry = new UserLog();
        $logEntry->setUserId($userId);
        $logEntry->setText($message);

        $this->em->persist($logEntry);
    }

    /**
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param \Galaxy\GameBundle\Service\InfoRemoteService $infoRemoteService
     */
    public function setInfoRemoteService(InfoRemoteService $infoRemoteService)
    {
        $this->infoRemote = $infoRemoteService;
    }

    public function setFrontendRemoteService($frontendRemote)
    {
        $this->frontendRemote = $frontendRemote;
    }

    public function setDocumentsRemoteService($documentsRemoteService)
    {
        $this->documentsRemote = $documentsRemoteService;
    }

    public function setBlackPointProcess($blackPointProcess)
    {
        $this->blackPointProcess = $blackPointProcess;
    }

}