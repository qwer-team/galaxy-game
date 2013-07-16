<?php

namespace Galaxy\GameBundle\Service;

use Galaxy\GameBundle\Entity\Question;
use Doctrine\ORM\EntityManager;
use Galaxy\GameBundle\Service\InfoRemoteService;

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

    public function fail(Question $question)
    {
        $question = $this->blockQuestion($question);
        $messageId = $question->getMessageId();
        $this->frontendRemote->resetQuestion($question->getId(), false);
        //$messageInfo = $this->infoRemote->getQuestion($messageId);
        
        $this->em->clear();
    }

    public function success(Question $question)
    {
        $question = $this->blockQuestion($question);
        $this->frontendRemote->resetQuestion($question->getId(), true);
        
        $this->em->clear();
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



}