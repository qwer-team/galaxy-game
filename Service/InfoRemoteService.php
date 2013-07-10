<?php

namespace Galaxy\GameBundle\Service;
use Qwer\Curl\Curl;

class InfoRemoteService
{

    /**
     * @var string
     */
    private $findMessageUrl;

    public function getMessage($data)
    {
        $message = Curl::makeRequest($this->findMessageUrl, $data);
        return json_decode($message);
    }

    public function getQuestion($messageId)
    {
        $obj = new \stdClass();
        $obj->seconds = 6;

        return $obj;
    }
    
    public function setFindMessageUrl($findMessageUrl)
    {
        $this->findMessageUrl = $findMessageUrl;
    }

}