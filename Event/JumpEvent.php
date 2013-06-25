<?php

namespace Galaxy\GameBundle\Event;

use \Symfony\Component\EventDispatcher\Event;

class JumpEvent extends Event
{

    private $jump;
    private $response;

    function __construct($jump)
    {
        $this->jump = $jump;
    }

    /**
     * 
     * @return \Galaxy\GameBundle\Entity\Jump
     */
    public function getJump()
    {
        return $this->jump;
    }

    public function setJump($jump)
    {
        $this->jump = $jump;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setResponse($response)
    {
        $this->response = $response;
    }

}