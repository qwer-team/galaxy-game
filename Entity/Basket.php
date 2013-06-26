<?php

namespace Galaxy\GameBundle\Entity;

/**
 * Basket
 */
class Basket
{

    /**
     * @var integer
     */
    private $elementId;

    /**
     * @var boolean
     */
    private $bought = false;

    /**
     * @var integer
     */
    private $jumpsRemain;

    /**
     * @var integer
     */
    private $userId;

    /**
     * @var integer
     */
    private $subelementId;

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
    private $restore;

    /**
     * @var integer
     */
    private $id;

    /**
     * Set elementId
     *
     * @param integer $elementId
     * @return Basket
     */
    public function setElementId($elementId)
    {
        $this->elementId = $elementId;

        return $this;
    }

    /**
     * Get elementId
     *
     * @return integer 
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * Set bought
     *
     * @param boolean $bought
     * @return Basket
     */
    public function setBought($bought)
    {
        $this->bought = $bought;

        return $this;
    }

    /**
     * Get bought
     *
     * @return boolean 
     */
    public function getBought()
    {
        return $this->bought;
    }

    /**
     * Set jumpsRemain
     *
     * @param integer $jumpsRemain
     * @return Basket
     */
    public function setJumpsRemain($jumpsRemain)
    {
        $this->jumpsRemain = $jumpsRemain;

        return $this;
    }

    /**
     * Get jumpsRemain
     *
     * @return integer 
     */
    public function getJumpsRemain()
    {
        return $this->jumpsRemain;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return Basket
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

    /**
     * Set subelementId
     *
     * @param integer $subelementId
     * @return Basket
     */
    public function setSubelementId($subelementId)
    {
        $this->subelementId = $subelementId;

        return $this;
    }

    /**
     * Get subelementId
     *
     * @return integer 
     */
    public function getSubelementId()
    {
        return $this->subelementId;
    }

    /**
     * Set x
     *
     * @param integer $x
     * @return Basket
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
     * @return Basket
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
     * @return Basket
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
     * Set restore
     *
     * @param boolean $restore
     * @return Basket
     */
    public function setRestore($restore)
    {
        $this->restore = $restore;

        return $this;
    }

    /**
     * Get restore
     *
     * @return boolean 
     */
    public function getRestore()
    {
        return $this->restore;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    public function setCoordinates($coordinates)
    {
        $this->x = $coordinates[0];
        $this->y = $coordinates[1];
        $this->z = $coordinates[2];
    }

    public function subJumpsRemain()
    {
        $this->jumpsRemain--;
    }

}
