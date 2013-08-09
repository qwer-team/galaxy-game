<?php

namespace Galaxy\GameBundle\Listener;

use Galaxy\GameBundle\Event\SellElementEvent;

class SellElementListener
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     *
     * @var \Galaxy\GameBundle\Service\DocumentsRemoteService
     */
    private $documentsRemote;
    

    /**
     *
     * @var \Galaxy\GameBundle\Service\SpaceRemoteService 
     */
    private $spaceRemote;
    
    private $transUrl;
    private $spaceUrl;

    public function onEvent(SellElementEvent $event)
    {
        $userId = $event->getUserId();
        $elementId = $event->getElementId();
        $basket = $this->getBasket($userId, $elementId);

        $element = $this->spaceRemote->getElement($elementId);
        $funds = $this->documentsRemote->getFunds($userId);
        $this->em->getConnection()->beginTransaction();
        try {
            $this->transFunds($userId, $element, $funds, $basket);
            $basket->setBought(false);
            if($basket->getRestore()){
                $this->spaceRemote->restorePrize($basket);
            }
            $event->setResponse($basket->getElementId());
            $this->em->remove($basket);
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
        }
    }

    

    private function transFunds($userId, $element, $funds, $basket)
    {
        $cost = $this->spaceRemote->getCost($element, $basket, $funds);
        $data = array(
            'OA1' => $userId,
            'summa1' => $cost,
            'account' => 1
        );
        $response = json_decode($this->makeRequest($this->transUrl, $data));
        return $response;
    }

    private function getBasket($userId, $elementId)
    {
        $repo = $this->em->getRepository("GalaxyGameBundle:Basket");
        $criteria = array(
            "userInfo" => $userId,
            "elementId" => $elementId,
            "bought" => true,
        );
       
        $element = $repo->findOneBy($criteria);
        if (!$element) {
            throw new \Exception('baskets was not found');
        }
        return $element;
    }

    public function setEm($em)
    {
        $this->em = $em;
    }

    public function setDocumentsRemote($documentsRemote)
    {
        $this->documentsRemote = $documentsRemote;
    }

    public function setSpaceRemote($spaceRemote)
    {
        $this->spaceRemote = $spaceRemote;
    }

    public function setTransUrl($transUrl)
    {
        $this->transUrl = $transUrl;
    }
    
    public function setSpaceUrl($spaceUrl)
    {
        $this->spaceUrl = $spaceUrl;
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