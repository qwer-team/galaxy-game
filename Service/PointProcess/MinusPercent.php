<?php

namespace Galaxy\GameBundle\Service\PointProcess;

use Galaxy\GameBundle\Service\PointProcess\PointTypeProcess;
use Galaxy\GameBundle\Service\DocumentsRemoteService;

class MinusPercent implements PointTypeProcess
{

    private $documentService;
    private $url;

    public function setDocumentService(DocumentsRemoteService $documentService)
    {
        $this->documentService = $documentService;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function proceed($response, $userId)
    {
        $parameter = $response['subtype']['parameter'];
        //$documentsService = $this->container->get("document.remote_service");
        $fundsInfo = $this->documentService->getFunds($userId);
        $cash = $fundsInfo->active;
        $summa1 = $cash * $parameter / 100;
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa1,
            'account' => 1
        );
       //$url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($this->url, $data));

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