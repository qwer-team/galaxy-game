<?php

namespace Galaxy\GameBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Qwer\Curl\Curl;

class DocumentsRemoteService extends ContainerAware
{

    public function getFunds($userId)
    {
        $rawUrl = $this->container->getParameter("documents.get_funds.url");
        $url = str_replace("{userId}", $userId, $rawUrl);

        $response = json_decode($this->makeRequest($url));
        return $response;
    }

    public function depositeFunds($userId, $summa, $account)
    {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.deposite_funds.url");

        $response = json_decode($this->makeRequest($url, $data));

        return $response;
    }

    public function debitFunds($userId, $summa, $account)
    {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.debit_funds.url");

        $response = json_decode($this->makeRequest($url, $data));

        return $response;
    }
    public function transFunds($userId, $summa, $account)
    {
        $data = array(
            'OA1' => $userId,
            'summa1' => $summa,
            'account' => $account
        );
        $url = $this->container->getParameter("documents.trans_funds.url");

        $response = json_decode($this->makeRequest($url, $data));

        return $response;
    }

    private function makeRequest($url, $data = null)
    {
        return Curl::makeRequest($url, $data);
    }

}