<?php

namespace Galaxy\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserInfo
 */
class UserInfo {

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $x;

    /**
     * @var integer
     */
    private $y;

    /**
     * @var integer
     */
    private $z;

    /**
     * @var integer
     */
    private $totalJumps;

    /**
     * @var integer
     */
    private $superJumps;

    /**
     * @var integer
     */
    private $countMessages;

    /**
     * @var integer
     */
    private $countRentJumps;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Galaxy\GameBundle\Entity\Flipper
     */
    private $flipper;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    private $basket;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection 
     */
    private $questions;

    /**
     *
     * @var \Galaxy\GameBundle\Entity\Message 
     */
    private $message;
    private $minRadius;
    private $maxRadius;
    private $pointId;
    private $subElementId;
    private $subTypeId;
    private $zoneJumps;
    private $centralPointId;
    private $pointType;

    public function __construct() {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->basket = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getSubTypeId() {
        return $this->subTypeId;
    }

    public function setSubTypeId($subTypeId) {
        $this->subTypeId = $subTypeId;
    }

    public function getPointType() {
        return $this->pointType;
    }

    public function setPointType($pointType) {
        $this->pointType = $pointType;
    }

    public function getCentralPointId() {
        return $this->centralPointId;
    }

    public function setCentralPointId($centralPointId) {
        $this->centralPointId = $centralPointId;
    }

    public function getZoneJumps() {
        return $this->zoneJumps;
    }

    public function decZoneJump() {
        return $this->zoneJumps--;
    }

    public function setZoneJumps($zoneJumps) {
        $this->zoneJumps = $zoneJumps;
    }

    public function getMinRadius() {
        return $this->minRadius;
    }

    public function setMinRadius($minRadius) {
        $this->minRadius = $minRadius;
    }

    public function getMaxRadius() {
        return $this->maxRadius;
    }

    public function setMaxRadius($maxRadius) {
        $this->maxRadius = $maxRadius;
    }

    public function getPointId() {
        return $this->pointId;
    }

    public function setPointId($pointId) {
        $this->pointId = $pointId;
    }

    public function getSubElementId() {
        return $this->subElementId;
    }

    public function setSubElementId($subElementId) {
        $this->subElementId = $subElementId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserInfo
     */
    public function setUserId($userId) {
        $this->userId = $userId;

        return $this;
    }

    public function getCountRentJumps() {
        return $this->countRentJumps;
    }

    public function setCountRentJumps($countRentJumps) {
        $this->countRentJumps = $countRentJumps;
    }

    public function decCountRentJumps() {
        return $this->countRentJumps--;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set x
     *
     * @param integer $x
     * @return UserInfo
     */
    public function setX($x) {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return integer 
     */
    public function getX() {
        return $this->x;
    }

    public function getCountMessages() {
        return $this->countMessages;
    }

    public function setCountMessages($countMessages) {
        $this->countMessages = $countMessages;
    }

    public function increseCountMessages() {
        $this->countMessages++;
    }

    /**
     * Set y
     *
     * @param integer $y
     * @return UserInfo
     */
    public function setY($y) {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return integer 
     */
    public function getY() {
        return $this->y;
    }

    /**
     * Set z
     *
     * @param integer $z
     * @return UserInfo
     */
    public function setZ($z) {
        $this->z = $z;

        return $this;
    }

    /**
     * Get z
     *
     * @return integer 
     */
    public function getZ() {
        return $this->z;
    }

    /**
     * Set totalJumps
     *
     * @param integer $totalJumps
     * @return UserInfo
     */
    public function setTotalJumps($totalJumps) {
        $this->totalJumps = $totalJumps;

        return $this;
    }

    /**
     * Get totalJumps
     *
     * @return integer 
     */
    public function getTotalJumps() {
        return $this->totalJumps;
    }

    /**
     * Set superJumps
     *
     * @param integer $superJumps
     * @return UserInfo
     */
    public function setSuperJumps($superJumps) {
        $this->superJumps = $superJumps;

        return $this;
    }

    /**
     * Get superJumps
     *
     * @return integer 
     */
    public function getSuperJumps() {
        return $this->superJumps;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set flipper
     *
     * @param \Galaxy\GameBundle\Entity\Flipper $flipper
     * @return UserInfo
     */
    public function setFlipper(\Galaxy\GameBundle\Entity\Flipper $flipper) {
        $this->flipper = $flipper;

        return $this;
    }

    /**
     * Get flipper
     *
     * @return \Galaxy\GameBundle\Entity\Flipper 
     */
    public function getFlipper() {
        return $this->flipper;
    }

    public function subSuperJump() {
        $this->superJumps--;
    }

    public function addTotalJump() {
        $this->totalJumps++;
    }

    /**
     * Set x
     *
     * @param integer $x, $y, $z
     * @return UserInfo
     */
    public function setNewCoordinates(Jump $jump) {
        list($this->x, $this->y, $this->z) = $jump->getCoordinates();
        return $this;
    }

    public function getBasket() {
        return $this->basket;
    }

    public function setBasket($basket) {
        $this->basket = $basket;
    }

    public function getQuestions() {
        return $this->questions;
    }

    public function setQuestions($questions) {
        $this->questions = $questions;
    }

    public function getMessage() {
        return $this->message;
    }

    public function setMessage($message) {
        $this->message = $message;
    }

}
