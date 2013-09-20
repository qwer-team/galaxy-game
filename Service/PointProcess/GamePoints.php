<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;

class GamePoints implements PointTypeProcess
{
    
    public function proceed($parameter, $userId)
    {
        $documentsService = $this->container->get("document.remote_service");
        $fundsInfo = $documentsService->getFunds($userId);
        if($parameter < 0 && $fundsInfo->active < abs($parameter)){
            $cash = $fundsInfo->active;
        } else {
            $cash = abs($parameter);
        }
        $data = array(
            'OA1' => $userId,
            'summa1' => $cash,
            'account' => 1
        );
        $url = $parameter > 0 ? $this->container->getParameter("documents.trans_funds.url") : $this->container->getParameter("documents.debit_funds.url");

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