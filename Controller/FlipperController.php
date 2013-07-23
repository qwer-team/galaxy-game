<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Entity\Flipper;
use Galaxy\GameBundle\Form\FlipperType;

class FlipperController extends FOSRestController
{

    public function getFlipperAction($id)
    {
        $repo = $this->getFlipperRepo();
        $flipper = $repo->find($id);

        $view = $this->view($flipper);
        return $this->handleView($view);
    }

    public function postFlipperUpdateAction($id, Request $request)
    {
        $repo = $this->getFlipperRepo();
        $flipper = $repo->find($id);

        $form = $this->createForm(new FlipperType(), $flipper);
        $form->bindRequest($request);
        $result = array("result" => "fail", 'data' => $request->request->all());
        if ($form->isValid()) {
            $result["result"] = "success";
            $this->getDoctrine()->getEntityManager()->flush();
        } else {
            $result['error'] = $form->getErrorsAsString();
        }

        $view = $this->view($result);
        return $this->handleView($view);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getFlipperRepo()
    {
        $namespace = "GalaxyGameBundle:Flipper";
        $repo = $this->getDoctrine()->getRepository($namespace);

        return $repo;
    }

}