<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Description of BasketController
 *
 * @author root
 */
class BasketController extends FOSRestController
{

    public function getElementCountAction($elementId){
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository("GalaxyGameBundle:Basket");
        
        $criteria = array("elementId" => $elementId);
        $elements = $repo->findBy($criteria);
        
        $result = array("count" => count($elements));
        $view = $this->view($result);
        return $this->handleView($view);
    }

}