<?php

namespace Galaxy\GameBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;

class DocumentsRemoteService extends ContainerAware
{

    public function getFunds($userId)
    {
        $rawUrl = $this->container->getParameter("documents.get_funds.url");
        $url = str_replace("{userId}", $userId, $rawUrl);

        $response = json_decode($this->makeRequest($url));
        return $response;
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