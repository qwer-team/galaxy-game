<?php

namespace Galaxy\GameBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Galaxy\GameBundle\Entity\UserInfo;
use Galaxy\GameBundle\Entity\Flipper;

class UserInfoController extends FOSRestController {

    public function getGameUserInfoAction($id) {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);


        $questions = $info->getQuestions();
        $availableQuestions = new \Doctrine\Common\Collections\ArrayCollection();
        $allowedStatus = array(2, 3);
        foreach ($questions as $question) {
            if (in_array($question->getStatus(), $allowedStatus)) {
                $question->setRightAnswer(null);
                $question->setAnswers(null);
                $availableQuestions->add($question);
            }
        }
        $info->setQuestions($availableQuestions);
        $view = $this->view($info);
        return $this->handleView($view);
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getUserInfoRepo() {
        $namespace = "GalaxyGameBundle:UserInfo";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;
    }

    private function getFlipperRepo() {
        $namespace = "GalaxyGameBundle:Flipper";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;
    }

    /**
     * 
     * @return \Doctrine\ORM\EntityRepository
     */
    private function getBasketRepo() {
        $namespace = "GalaxyGameBundle:Basket";
        $em = $this->getDoctrine()->getEntityManager();
        $repo = $em->getRepository($namespace);
        return $repo;
    }

    public function getIncreaseMessagesAction($id) {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);
        $info->increseCountMessages();
        $this->getDoctrine()->getEntityManager()->flush();

        $view = $this->view($info);
        return $this->handleView($view);
    }

    public function getIncreaseFlipperAction($id, $flipperId) {
        $repo = $this->getUserInfoRepo();
        $flipperRepo = $this->getFlipperRepo();
        $flipper = $flipperRepo->find($flipperId);
        $info = $repo->find($id);
        $currentFlip = $info->getFlipper();
        if ($flipper->getId() - $currentFlip->getId() == 1) {
            $info->setFlipper($flipper);
            $info->setCountRentJumps($flipper->getRentDuration());
            $this->getDoctrine()->getEntityManager()->flush();
        }
        $view = $this->view($info);
        return $this->handleView($view);
    }

    public function getRadarResetAction($id) {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);
        $info->setMinRadius(0);
        $info->setMaxRadius(0);
        $info->setPointId(0);
        $info->setSubElementId(0);
        $info->setSubTypeId(0);
        $info->setZoneJumps(0);
        $info->setCentralPointId(0);
        $info->setPointType(0);
        $this->getDoctrine()->getEntityManager()->flush();

        $view = $this->view($info);
        return $this->handleView($view);
    }

    public function getBuyZoneAction($id, $jumps) {
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);

        $info->setZoneJumps($jumps);
        $this->getDoctrine()->getEntityManager()->flush();

        $view = $this->view($info);
        return $this->handleView($view);
    }

    public function getRadarStartAction($id, $type) {
        $spaceService = $this->get("space.remote_service");
        $result['result'] = 'fail';
        $repo = $this->getUserInfoRepo();
        $info = $repo->find($id);
        $flipper = $info->getFlipper();
        $data = array(
            "type" => $type,
            "x" => $info->getX(),
            "y" => $info->getY(),
            "z" => $info->getZ(),
            "r1" => $flipper->getNextPointDistance(),
            "r2" => $flipper->getMaxJump(),
        );
        $response = $spaceService->radarStart($data);
        if ($response->result == 'success') {
            $result['result'] = 'success';
            $data = $this->getZoneRadius($info, $response->point->id);
            $info->setMinRadius($data['minRadius']);
            $info->setMaxRadius($data['maxRadius']);
            $info->setPointId($response->point->id);
            $info->setPointType($type);
            $info->setSubTypeId($response->point->subtype);
            $info->setCentralPointId($this->getId($info->getX(), $info->getY(), $info->getZ()));
            if ($type == 4) {
                $info->setSubElementId($response->subelement->id);
            }
            $this->getDoctrine()->getEntityManager()->flush();
        }
        
        $view = $this->view($result);
        
        return $this->handleView($view);
    }

    private function getZoneRadius(UserInfo $info, $id) {
        $flipper = $info->getFlipper();
        $minDeltaRadius = rand($flipper->getFirstLeftBorder(), $flipper->getSecondLeftBorder());
        $maxDeltaRadius = rand($flipper->getFirstRightBorder(), $flipper->getSecondRightBorder());
        $x = $info->getX();
        $y = $info->getY();
        $z = $info->getZ();
        $pointCoord = $this->getCoords($id);
        $dx = $x - $pointCoord['x'];
        $dy = $y - $pointCoord['y'];
        $dz = $z - $pointCoord['z'];
        $distance1 = sqrt(pow($dx, 2) + pow($dy, 2) + pow($dz, 2));

        $distance2 = sqrt(pow(999 - abs($dx), 2) + pow($dy, 2) + pow($dz, 2));
        $distance3 = sqrt(pow($dx, 2) + pow(999 - abs($dy), 2) + pow($dz, 2));
        $distance4 = sqrt(pow($dx, 2) + pow($dy, 2) + pow(999 - abs($dz), 2));

        $distance5 = sqrt(pow(999 - abs($dx), 2) + pow(999 - abs($dy), 2) + pow($dz, 2));
        $distance6 = sqrt(pow(999 - abs($dx), 2) + pow($dy, 2) + pow(999 - abs($dz), 2));
        $distance7 = sqrt(pow($dx, 2) + pow(999 - abs($dy), 2) + pow(999 - abs($dz), 2));

        $distance8 = sqrt(pow(999 - abs($dx), 2) + pow(999 - abs($dy), 2) + pow(999 - abs($dz), 2));
        $distance = min($distance1, $distance2, $distance3, $distance4, $distance5, $distance6, $distance7, $distance8);
        $result['minRadius'] = max($flipper->getNextPointDistance(), $distance - $minDeltaRadius);
        $result['maxRadius'] = min($flipper->getMaxJump(), $distance + $maxDeltaRadius);

        return $result;
    }

    private function getCoords($id) {
        $id--;
        $x = $id % 1000;
        $id -= $x;
        $x++;

        $y = $id % 1000000 / 1000;
        $id -= $y * 1000;
        $y++;
        $z = $id % 1000000000 / 1000000;
        $z++;

        return array(
            "x" => $x,
            "y" => $y,
            "z" => $z,
        );
    }

    private function getId($x, $y, $z) {
        $id = $x + ($y - 1) * 1000 + ($z - 1) * 1000000;

        return $id;
    }

}