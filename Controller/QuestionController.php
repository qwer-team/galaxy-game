<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class QuestionController extends FOSRestController
{

    private $delta;
    public function getQuestionAction($questionId)
    {
        $repo = $this->getQuestionRepository();
        $question = $repo->find($questionId);
        
        $remoteService = $this->get('info.remote.service');
        
        $questionBody = $remoteService->getQuestion($question->getMessageId());
        
        $seconds = $questionBody->seconds + $this->delta;
        $expires = new \DateTime();
        $expires->add(\DateInterval::createFromDateString("$seconds seconds"));
        $data = array(
            "id" => $questionId,
            "expires" => $expires,
        );
        $backClient = $this->get('laelaps.gearman.client');
        $backClient->doBackground('close_question', serialize($data));
        
        $view = $this->view($data);
        return $this->handleView($view);
    }

    public function postAnswerQuestion($questionId)
    {
        $view = $this->view(array("result" => "ok"));
        return $this->handleView($view);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getQuestionRepository()
    {
        $namespace = "GalaxyGameBundle:Question";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;    
    }

}