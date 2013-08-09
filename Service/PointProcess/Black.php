<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;
use Symfony\Component\DependencyInjection\ContainerAware;

class Black extends ContainerAware implements PointTypeProcess
{
    public function proceed($parameter, $userId)
    {
        $days = $this->container->getParameter("user.lock.days");
        $interval = str_replace("{days}", $parameter, $days);
        $date = new \DateTime();
        $date->modify($interval);
        //$date->add(new \DateInterval($interval));
        $data = array(
                "lockedExpiresAt" => $date->format("Y-m-d H:i:s")
         );
        $rawUrl = $this->container->getParameter("user.frontend.lock");
        $url = str_replace("{userId}", $userId, $rawUrl);
        $response = json_decode($this->makeRequest($url, $data));
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