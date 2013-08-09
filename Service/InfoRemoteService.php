<?php

namespace Galaxy\GameBundle\Service;

use Qwer\Curl\Curl;

class InfoRemoteService
{

    /**
     * @var string
     */
    private $findMessageUrl;

    /**
     * @var string
     */
    private $getMessageUrl;

    public function getMessage($data)
    {
        $message = Curl::makeRequest($this->findMessageUrl, $data);
        return json_decode($message);
    }

    public function getQuestion($messageId)
    {
        $url = str_replace("{id}", $messageId, $this->getMessageUrl);
        $message = Curl::makeRequest($url);

        return json_decode($message);
    }

    public function setFindMessageUrl($findMessageUrl)
    {
        $this->findMessageUrl = $findMessageUrl;
    }

    public function setGetMessageUrl($getMessageUrl)
    {
        $this->getMessageUrl = $getMessageUrl;
    }

}