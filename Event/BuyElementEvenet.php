<?php

namespace Galaxy\GameBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class BuyElementEvenet extends Event
{

    private $userId;
    private $response;

    public function getUserId()
    {
        return $this->userId;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

    function __construct($userId)
    {
        $this->userId = $userId;
    }

}