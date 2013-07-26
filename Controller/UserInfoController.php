<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Entity\UserInfo;
use Galaxy\GameBundle\Entity\Flipper;

class UserInfoController extends FOSRestController
{

    public function getGameUserInfoAction($id)
    {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);


        $questions = $info->getQuestions();
        $availableQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $allowedStatus = array(2, 3);
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

    private function getFlipperRepo()
    {
        $namespace = "GalaxyGameBundle:Flipper";
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

    public function getIncreaseFlipperAction($id, $flipperId)
    {
        $repo = $this->getUserInfoRepo();
        $flipperRepo = $this->getFlipperRepo();
        $flipper = $flipperRepo->find($flipperId);
        $info = $repo->find($id);
        $currentFlip = $info->getFlipper();
        if ($flipper->getId() - $currentFlip->getId() == 1) {
            $info->setFlipper($flipper);
            $info->setCountRentJumps($flipper->getRentDuration());
            $this->getDoctrine()->getEntityManager()->flush();
        }
        $view = $this->view($info);
        return $this->handleView($view);
    }

    public function getRadarResetAction($id)
    {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);
        $info->setLeftRadius(0);
        $info->setRightRadius(0);
        $info->setPointId(0);
        $info->setElementId(0);
        $this->getDoctrine()->getEntityManager()->flush();

        $view = $this->view($info);
        return $this->handleView($view);
    }

    public function getRadarStartAction($id, $type)
    {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);
        $flipper = $info->getFlipper();
        $minRadius = $this->getRandomRadius($flipper->getFirstLeftBorder(), $flipper->getFirstRightBorder());
        $maxRadius = $this->getRandomRadius($flipper->getSecondLeftBorder(), $flipper->getSecondRightBorder());
        $x = $info->getX();
        $y = $info->getY();
        $z = $info->getZ();
        $data = array(
            "type" => $type,
            "x" => $x,
            "y" => $y,
            "z" => $z,
            "r1" => $minRadius,
            "r2" => $maxRadius,
            );
        $spaceService = $this->get("space.remote_service");
        $result = $spaceService->radarStart($data);
        
        $view = $this->view($result);
        return $this->handleView($view);
    }
    
    private function getRandomRadius($left, $right)
    {
        return rand($left, $right);
    }

}