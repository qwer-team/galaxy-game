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

    public function proceed($response, $userId)
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
        $message->setImage($messageData->image);
        $message->setText($messageData->text);

        $userInfo = $this->getUserInfo($userId);
        $userInfo->setMessage($message);

        $this->em->persist($message);
        $question = new Question();
        $question->setMessageId($messageData->id);
        $question->setJumpsToQuestion($messageData->jumpsToQuestion);
        $question->setRightAnswer($messageData->rightAnswer);
        $question->setSeconds($messageData->seconds);
        $question->setUserInfo($userInfo);

        $answers = array();
        foreach ($messageData->answers as $answer) {
            $answers[] = $answer->answer;
        }
        $question->setAnswers($answers);
        $this->em->persist($question);
        try {
            $this->em->flush();
        } catch (\Exception $e) {
            $this->logger->err($e->getMessage());
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