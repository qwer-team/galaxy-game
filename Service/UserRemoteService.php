<?php

namespace Galaxy\GameBundle\Service;

class UserRemoteService
{

    public function getUser($userId)
    {
        $rawUrl = $this->container->getParameter("get.user.url");
        $url = str_replace("{userId}", $userId, $rawUrl);
        $response = $this->makeRequest($url);
        $user = json_decode($response);
        return $user;
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