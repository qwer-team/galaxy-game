<?php

namespace Galaxy\GameBundle\Listener;

use Galaxy\GameBundle\Event\BuyElementEvenet;

class BuyElementListener
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
    private $debitUrl;

    public function onEvent(BuyElementEvenet $event)
    {
        $userId = $event->getUserId();
        $basket = $this->getBasket($userId);

        $element = $this->spaceRemote->getElement($basket->getElementId());
        $funds = $this->documentsRemote->getFunds($userId);


        if (!$this->checkFunds($element, $funds)) {
            throw new \Exception('not anought money');
        }

        $this->em->getConnection()->beginTransaction();

        try {
            $this->debitFunds($userId, $element);
            $basket->setBought(true);
            
            $event->setResponse($basket->getElementId());
            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->em->close();
        }
    }

    private function checkFunds($element, $funds)
    {
        if ($element->account == 1) {
            if ($funds->active < $element->price) {
                return false;
            }
        } else {
            if ($funds->deposite < $element->prize) {
                return false;
            }
        }
        return true;
    }

    private function debitFunds($userId, $element)
    {
        $data = array(
            'OA1' => $userId,
            'summa1' => $element->price,
            'account' => $element->account,
        );
        $response = json_decode($this->makeRequest($this->debitUrl, $data));
        return $response;
    }

    private function getBasket($userId)
    {
        $repo = $this->em->getRepository("GalaxyGameBundle:Basket");
        $criteria = array(
            "userInfo" => $userId,
            "bought" => false,
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

    public function setDebitUrl($debitUrl)
    {
        $this->debitUrl = $debitUrl;
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