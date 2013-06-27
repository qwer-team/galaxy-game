<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Event\BuyElementEvenet;

/**
 * Description of BasketController
 *
 * @author root
 */
class BasketController extends FOSRestController
{

    public function getElementCountAction($elementId)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("GalaxyGameBundle:Basket");

        $criteria = array("elementId" => $elementId);
        $elements = $repo->findBy($criteria);

        $result = array("count" => count($elements));
        $view = $this->view($result);
        return $this->handleView($view);
    }

    public function getElementBuyAction($userId)
    {
        $response = array("result" => 'success');

        $event = new BuyElementEvenet($userId);
        $dispatcher = $this->get("event_dispatcher");

        try {
            $dispatcher->dispatch("galaxy.game.buy_element", $event);
            $response = array(
                "result" => "success",
                "elementId" => $event->getResponse(),
            );
        } catch (\Exception $exception) {
            $response = array("result" => "fail");
        }

        $view = $this->view($response);
        return $this->handleView($view);
    }

}