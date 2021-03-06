<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;
use Galaxy\GameBundle\Entity\Message as MessageEntity;
use Galaxy\GameBundle\Entity\Question;

class Message implements PointTypeProcess
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     *
     * @var \Galaxy\GameBundle\Service\UserRemoteService 
     */
    private $userRemoteService;

    /**
     *
     * @var \Galaxy\GameBundle\Service\InfoRemoteService 
     */
    private $infoRemoteService;
    private $logger;

    public function proceed($parameter, $userId)
    {
        $user = $this->userRemoteService->getUser($userId);
        $birthday = new \DateTime($user->birthday);
        $date = new \DateTime();
        $interval = $date->diff($birthday);

        $age = $interval->y;

        $data = array(
            "age" => $age
        );
        $messageData = $this->infoRemoteService->getMessage($data);
        $message = new MessageEntity();
        if(isset($messageData->img1)){
            $message->setImage1($messageData->img1);
        }
        if(isset($messageData->img2)){
            $message->setImage2($messageData->img2);
        }
        if(isset($messageData->img3)){
            $message->setImage3($messageData->img3);
        }
        
        $message->setText($messageData->text);

        $userInfo = $this->getUserInfo($userId);
        $userInfo->setMessage($message);

        $this->em->persist($message);
        $question = new Question();
        $question->setText($messageData->question);
        $question->setMessageId($messageData->id);
        $question->setJumpsToQuestion($messageData->jumpsToQuestion);
        $question->setRightAnswer($messageData->rightAnswer);
        $question->setSeconds($messageData->seconds);
        $question->setImage1(isset($messageData->img1) ? $messageData->img1 : Null);
        $question->setImage2(isset($messageData->img2) ? $messageData->img2 : Null);
        $question->setImage3(isset($messageData->img3) ? $messageData->img3 : Null);
        $question->setUserInfo($userInfo);

        $answers = array();
        foreach ($messageData->answers as $answer) {
            $answers[] = $answer->answer;
        }
        $question->setAnswers($answers);
        $this->em->persist($question);
        try {
            $this->em->flush();
            return $messageData;
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
            $this->logger->err(print_r( $message, true));
        }
    }

    /**
     * 
     * @param \Doctrine\ORM\EntityManager $entityManager
     */
    public function setEntityManager($entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param integer $userId
     * @return \Galaxy\GameBundle\Entity\UserInfo
     */
    private function getUserInfo($userId)
    {
        $namespace = "GalaxyGameBundle:UserInfo";
        $repo = $this->em->getRepository($namespace);

        return $repo->findOneByUserId($userId);
    }

    public function setUserRemoteService($userRemoteService)
    {
        $this->userRemoteService = $userRemoteService;
    }

    public function setInfoRemoteService($infoRemoteService)
    {
        $this->infoRemoteService = $infoRemoteService;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

}