<?php

namespace Galaxy\GameBundle\Service;

use Qwer\Curl\Curl;

class FrontendRemoteService
{

    private $resetUrl;

    public function resetQuestion($questionId, $result)
    {
        $resultCode = $result ? 1 : 2;

        $search = array(
            "{questionId}", "{result}"
        );
        $replace = array(
            $questionId, $resultCode
        );
        
        $url = str_replace($search, $replace, $this->resetUrl);
        
        $response = Curl::makeRequest($url);
        return json_decode($response);
    }

    public function setResetUrl($resetUrl)
    {
        $this->resetUrl = $resetUrl;
    }

}