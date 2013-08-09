<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;
use Symfony\Component\DependencyInjection\ContainerAware;

class PlusPercent extends ContainerAware implements PointTypeProcess
{
    public function proceed($parameter, $userId)
    {
        $documentsService = $this->container->get("document.remote_service");
        $fundsInfo = $documentsService->getFunds($userId);
        $cash = $fundsInfo->active;
        $summa1 = $cash * $parameter / 100;
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa1,
            'account' => 1
        );
        $url = $this->container->getParameter("documents.trans_funds.url");

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