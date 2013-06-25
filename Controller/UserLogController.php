<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Entity\UserLog;

class UserLogController extends FOSRestController
{

    public function getGameUserLogAction($id, $page, $length)
    {
        $repo = $this->getUserLogRepo();
        $qb = $repo->createQueryBuilder('log');
        $qb->where("log.userId = :userId");
        $qb->orderBy('log.date', 'DESC');
        $firstResult = $length * ($page - 1);
        $qb->setFirstResult($firstResult)->setMaxResults($length);
        $qb->setParameter("userId", $id);

        $result = $qb->getQuery()->getResult();
        $view = $this->view($result);
        return $this->handleView($view);
    
    }
    
    public function getGameLogCountAction($userId)
    {
        $repo = $this->getUserLogRepo();
        $count = count($repo->findByUserId($userId));
        
        $view = $this->view(array("count" => $count));
        return $this->handleView($view);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserLogRepo()
    {
        $namespace = "GalaxyGameBundle:UserLog";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;    
    }

}