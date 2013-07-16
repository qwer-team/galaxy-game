<?php

namespace Galaxy\GameBundle\Service;

use Qwer\Curl\Curl;

class UserRemoteService
{

    /**
     * @var string 
     */
    private $getUserUrl;

    public function getUser($userId)
    {
        $url = str_replace("{userId}", $userId, $this->getUserUrl);
        $response = Curl::makeRequest($url);
        $user = json_decode($response);
        return $user;
    }

    public function setGetUserUrl($getUserUrl)
    {
        $this->getUserUrl = $getUserUrl;
    }

}