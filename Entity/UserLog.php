<?php

namespace Galaxy\GameBundle\Entity;

use Galaxy\GameBundle\Entity\Jump;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserLog
 */
class UserLog {

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $text;
    
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
     * @var \DateTime
     */
    private $date;

    function __construct() {
        $this->date = new \DateTime();
    }

    public function getX() {
        return $this->x;
    }

    public function setX($x) {
        $this->x = $x;
    }

    public function getY() {
        return $this->y;
    }

    public function setY($y) {
        $this->y = $y;
    }

    public function getZ() {
        return $this->z;
    }

    public function setZ($z) {
        $this->z = $z;
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
     * Set text
     *
     * @param string $text
     * @return UserLog
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText() {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return UserLog
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }

    public function getUserId() {
        return $this->userId;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserLog
     */
    public function setUserId($userId) {
        $this->userId = $userId;
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

}
