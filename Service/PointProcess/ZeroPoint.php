<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Symfony\Component\DependencyInjection\ContainerAware;
use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;

class ZeroPoint extends ContainerAware implements PointTypeProcess
{

    public function proceed($parameter, $userId)
    {
        $userInfo = $this->getUserInfo($userId);
        $flipperId = $userInfo->getFlipper()->getId();
        $documentsService = $this->container->get("document.remote_service");
        $fundsInfo = $documentsService->getFunds($userId);
        $active = $fundsInfo->active;
        $newFlipperId = 1;

        if ($newFlipperId != $flipperId) {
            $newFlipper = $this->getNewFlipper($newFlipperId);
            $userInfo->setFlipper($newFlipper);
        }
        $userInfo->getSuperJumps() ? $userInfo->setSuperJumps(0) : '';
        $userInfo->getX() != 1 ? $userInfo->setX(1) : '';
        $userInfo->getY() != 1 ? $userInfo->setY(1) : '';
        $userInfo->getZ() != 1 ? $userInfo->setZ(1) : '';


        $data = array(
            'OA1' => $userId,
            'summa1' => $active,
            'account' => 1
        );
        $url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($url, $data));

        $this->container->get("doctrine.orm.entity_manager")->flush();
    }

    private function makeRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (!is_null($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
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