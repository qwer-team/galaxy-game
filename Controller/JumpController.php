<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Form\JumpType;
use Galaxy\GameBundle\Entity\Jump;
use Galaxy\GameBundle\Event\JumpEvent;

class JumpController extends FOSRestController
{

    public function jumpAction(Request $request)
    {
        $jump = new Jump();
        $form = $this->createForm(new JumpType(), $jump);
        $form->bind($request);

        $result = array("result" => "fail validate");
        $params = print_r($request->request->all(),true);
        if ($form->isValid()) {
            $event = new JumpEvent($jump);
            $dispatcher = $this->get("event_dispatcher");
            try {
                $dispatcher->dispatch("galaxy.game.jump", $event);
                $result = array(
                    "result" => "success", 
                    "response" => $event->getResponse(),
                    "params" => $params,
                );
            } catch (\Exception $exception) {
                $result = array("result" => "failexc");
            }
        }

        $view = $this->view($result);
        return $this->handleView($view);
    }

}