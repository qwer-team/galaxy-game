<?php

namespace Galaxy\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jump
 */
class Jump
{

    /**
     * @var integer
     */
    private $id;

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
     * @var boolean
     */
    private $superjump;

    /**
     * @var integer
     */
    private $userId;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set x
     *
     * @param integer $x
     * @return Jump
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return integer 
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param integer $y
     * @return Jump
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return integer 
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set z
     *
     * @param integer $z
     * @return Jump
     */
    public function setZ($z)
    {
        $this->z = $z;

        return $this;
    }

    /**
     * Get z
     *
     * @return integer 
     */
    public function getZ()
    {
        return $this->z;
    }

    /**
     * Set superjump
     *
     * @param boolean $superjump
     * @return Jump
     */
    public function setSuperjump($superjump)
    {
        $this->superjump = $superjump;

        return $this;
    }

    /**
     * Get superjump
     *
     * @return boolean 
     */
    public function getSuperjump()
    {
        return $this->superjump;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Jump
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    public function getCoordinates()
    {
        return array(
            $this->x,
            $this->y,
            $this->z,
        );
    }

}
