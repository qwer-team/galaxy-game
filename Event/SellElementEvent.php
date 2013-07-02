<?php

namespace Galaxy\GameBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class SellElementEvent extends Event
{

    private $userId;
    private $elementId;
    private $response;

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
    
    public function getElementId()
    {
        return $this->elementId;
    }

    public function setElementId($elementId)
    {
        $this->elementId = $elementId;
    }

    
    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    function __construct($userId, $elementId)
    {
        $this->userId = $userId;
        $this->elementId = $elementId;
    }

}