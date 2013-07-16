<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Entity\UserInfo;

class UserInfoController extends FOSRestController
{

    public function getGameUserInfoAction($id)
    {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);


        $questions = $info->getQuestions();
        $availableQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $allowedStatus = array(2,3);
        foreach ($questions as $question) {
            if (in_array($question->getStatus(), $allowedStatus)) {
                $question->setRightAnswer(null);
                $question->setAnswers(null);
                $availableQuestions->add($question);
            }
        }
        $info->setQuestions($availableQuestions);
        $view = $this->view($info);
        return $this->handleView($view);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserInfoRepo()
    {
        $namespace = "GalaxyGameBundle:UserInfo";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getBasketRepo()
    {
        $namespace = "GalaxyGameBundle:Basket";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;

    }

    public function getIncreaseMessagesAction($id)
    {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);
        $info->increseCountMessages();
        $this->getDoctrine()->getEntityManager()->flush();
        
        $view = $this->view($info);
        return $this->handleView($view);

    }

}