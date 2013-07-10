<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;
use Galaxy\GameBundle\Entity\Message;
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

    public function proceed($response, $userId)
    {
        $user = $this->userRemoteService->getUser($userId);
        $interval = $user->birthday->sub(new \DateTime);

        $age = $interval->y;
        
        $data = array(
            "age" => $age
        );
        $messageData = $this->infoRemoteService->getMessage($data);
        $message = new Message();
        $message->setImage($messageData->image);
        $message->setText($messageData->text);
        
        $userInfo = $this->getUserInfo($userId);
        $userInfo->setMessage($message);
        
        $question = new Question();
        
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

}