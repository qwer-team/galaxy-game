<?php

namespace Galaxy\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Flipper
 */
class Flipper
{
    /**
     * @var integer
     */
    protected $id;
    protected $title;
    protected $maxJump;
    protected $costJump;
    protected $impossibleJumpHint;
    protected $paymentFromDeposit;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getMaxJump()
    {
        return $this->maxJump;
    }

    public function setMaxJump($maxJump)
    {
        $this->maxJump = $maxJump;
    }

    public function getCostJump()
    {
        return $this->costJump;
    }

    public function setCostJump($costJump)
    {
        $this->costJump = $costJump;
    }

    public function getImpossibleJumpHint()
    {
        return $this->impossibleJumpHint;
    }

    public function setImpossibleJumpHint($impossibleJumpHint)
    {
        $this->impossibleJumpHint = $impossibleJumpHint;
    }
    
    public function getPaymentFromDeposit()
    {
        return $this->paymentFromDeposit;
    }

    public function setPaymentFromDeposit($paymentFromDeposit)
    {
        $this->paymentFromDeposit = false;//$paymentFromDeposit;
    }




}
