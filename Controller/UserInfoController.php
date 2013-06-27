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

}