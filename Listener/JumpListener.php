<?php

namespace Galaxy\GameBundle\Listener;

use Galaxy\GameBundle\Event\JumpEvent;
use Symfony\Component\DependencyInjection\ContainerAware;
use Galaxy\GameBundle\Entity\UserInfo;

class JumpListener extends ContainerAware
{

    public function onEvent(JumpEvent $event)
    {
        $jump = $event->getJump();

        $userId = $jump->getUserId();
        $userInfo = $this->getUserInfo($userId);


        if ($jump->getSuperjump()) {
            $userInfo->subSuperJump();
        }
        $userInfo->addTotalJump();

        $em = $this->getEntityManager();


        $em->getConnection()->beginTransaction();

        try {
            $em->flush();
            $this->debitFunds($userInfo);
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
        }
    }

    /**
     * 
     * @param type $userId
     * @return \Galaxy\GameBundle\Entity\UserInfo
     */
    private function getUserInfo($userId)
    {
        $em = $this->getEntityManager();
        $namespace = "GalaxyGameBundle:UserInfo";
        $repo = $em->getRepository($namespace);

        return $repo->findOneByUserId($userId);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $em = $this->container->get("doctrine.orm.entity_manager");
    }

    private function debitFunds(UserInfo $userInfo)
    {
        $flipper = $userInfo->getFlipper();
        $data = array(
            'OA1' => $userInfo->getUserId(),
            'summa1' => $flipper->getCostJump(),
            'account' => ($flipper->getPaymentFromDeposit() ? 2 : 1)
        );
        $url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($url, $data));
        
        return $response;
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

}