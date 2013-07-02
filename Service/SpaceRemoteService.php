<?php

namespace Galaxy\GameBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAware;

class SpaceRemoteService extends ContainerAware
{

    public function getElement($id)
    {
        $url = $this->container->getParameter("space.element.url");

        $response = json_decode($this->makeRequest($url . $id));
        return $response;
    }

    public function restorePrize($oldPrize)
    {
        $url = $this->container->getParameter("space.restore_prize.url");
        $data = array(
            "subelement" => $oldPrize->getSubelementId(),
            "x" => $oldPrize->getX(),
            "y" => $oldPrize->getY(),
            "z" => $oldPrize->getZ(),
        );

        $this->makeRequest($url, $data);
    }

    public function getCost($element, $basket, $funds)
    {
        $url = $this->container->getParameter("get.prize_list.url");
        $response = $this->makeRequest($url);
        $prizeList = json_decode($response, true);
        if ($element->account == 1) {
            $rate = 1;
        } else {
            $acc = "3";
            $rate = $funds->rates->$acc;
        }
        $cost = $element->price * ($basket->getJumpsRemain() / $element->available) * $rate * (1 - ($prizeList[$element->prizeId]["penalty"] / 100));
        return ceil($cost);
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