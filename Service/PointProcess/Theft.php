<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Symfony\Component\DependencyInjection\ContainerAware;
use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;

class Theft extends ContainerAware implements PointTypeProcess
{
    public function proceed($parameter, $userId)
    {
        $userInfo = $this->getUserInfo($userId);
        $flipperId = $userInfo->getFlipper()->getId();
        $newFlipperId = $flipperId - $parameter;
        $newFlipperId = ($newFlipperId >= 1 ? $newFlipperId : 1); 
        if($newFlipperId != $flipperId){
            $newFlipper = $this->getNewFlipper($newFlipperId);
            $userInfo->setFlipper($newFlipper);
        }
        
        $this->container->get("doctrine.orm.entity_manager")->flush();
    }
    
    /**
     * 
     * @param type $userId
     * @return \Galaxy\GameBundle\Entity\UserInfo
     */
    private function getUserInfo($userId)
    {
        $em = $this->container->get("doctrine.orm.entity_manager");
        $namespace = "GalaxyGameBundle:UserInfo";
        $repo = $em->getRepository($namespace);

        return $repo->findOneByUserId($userId);
    }
    
    
    /**
     * 
     * @param type $flipperId
     * @return \Galaxy\GameBundle\Entity\Flipper
     */
    private function getNewFlipper($flipperId)
    {
        $em = $this->container->get("doctrine.orm.entity_manager");
        $namespace = "GalaxyGameBundle:Flipper";
        $repo = $em->getRepository($namespace);

        return $repo->find($flipperId);
    }
    
}