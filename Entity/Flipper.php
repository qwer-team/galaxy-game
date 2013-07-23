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
     * @var integer
     */
    private $journalDuration;

    /**
     * @var integer
     */
    private $rentDuration;

    /**
     * @var integer
     */
    private $rentCost;

    /**
     * @var integer
     */
    private $countRentMess;

    /**
     * @var integer
     */
    private $activeAccLess;

    /**
     * @var string
     */
    private $activeAccHint;

    /**
     * @var string
     */
    private $messageBuyHint;

    /**
     * @var integer
     */
    private $radarCost;
    private $radarSpec;

    /**
     * @var integer
     */
    private $nextPointDistance;

    /**
     * @var integer
     */
    private $firstLeftBorder;

    /**
     * @var integer
     */
    private $secondLeftBorder;

    /**
     * @var integer
     */
    private $firstRightBorder;

    /**
     * @var integer
     */
    private $secondRightBorder;

    /**
     * @var string
     */
    private $distanceHint;

    /**
     * @var integer
     */
    private $searchZoneCost;
    private $searchZoneSpec;

    /**
     * @var integer
     */
    private $leftBorderSearchX;

    /**
     * @var integer
     */
    private $rightBorderSearchX;

    /**
     * @var integer
     */
    private $deltaSearchX;

    /**
     * @var integer
     */
    private $leftBorderSearchY;

    /**
     * @var integer
     */
    private $rightBorderSearchY;

    /**
     * @var integer
     */
    private $deltaSearchY;

    /**
     * @var integer
     */
    private $leftBorderSearchZ;

    /**
     * @var integer
     */
    private $rightBorderSearchZ;

    /**
     * @var integer
     */
    private $deltaSearchZ;

    /**
     * @var integer
     */
    private $incLeftSearchRadius;

    /**
     * @var integer
     */
    private $incRightSearchRadius;

    /**
     * @var integer
     */
    private $firstZoneDuration1;

    /**
     * @var integer
     */
    private $firstZoneCost1;

    /**
     * @var boolean
     */
    private $firstZoneSpec1;

    /**
     * @var integer
     */
    private $firstZoneDuration2;

    /**
     * @var integer
     */
    private $firstZoneCost2;

    /**
     * @var boolean
     */
    private $firstZoneSpec2;

    /**
     * @var integer
     */
    private $firstZoneDuration3;

    /**
     * @var integer
     */
    private $firstZoneCost3;

    /**
     * @var boolean
     */
    private $firstZoneSpec3;

    /**
     * @var integer
     */
    private $firstZoneDuration4;

    /**
     * @var integer
     */
    private $firstZoneCost4;

    /**
     * @var boolean
     */
    private $firstZoneSpec4;

    /**
     * @var integer
     */
    private $secondZoneDuration1;

    /**
     * @var integer
     */
    private $secondZoneCost1;

    /**
     * @var boolean
     */
    private $secondZoneSpec1;

    /**
     * @var integer
     */
    private $secondZoneDuration2;

    /**
     * @var integer
     */
    private $secondZoneCost2;

    /**
     * @var boolean
     */
    private $secondZoneSpec2;

    /**
     * @var integer
     */
    private $secondZoneDuration3;

    /**
     * @var integer
     */
    private $secondZoneCost3;

    /**
     * @var boolean
     */
    private $secondZoneSpec3;

    /**
     * @var integer
     */
    private $secondZoneDuration4;

    /**
     * @var integer
     */
    private $secondZoneCost4;

    /**
     * @var boolean
     */
    private $secondZoneSpec4;

    public function getRadarSpec()
    {
        return $this->radarSpec;
    }

    public function setRadarSpec($radarSpec)
    {
        $this->radarSpec = $radarSpec;
    }

    public function getSearchZoneSpec()
    {
        return $this->searchZoneSpec;
    }

    public function setSearchZoneSpec($searchZoneSpec)
    {
        $this->searchZoneSpec = $searchZoneSpec;
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
        $this->paymentFromDeposit = $paymentFromDeposit;
    }

    /**
     * Set journalDuration
     *
     * @param integer $journalDuration
     * @return Flipper
     */
    public function setJournalDuration($journalDuration)
    {
        $this->journalDuration = $journalDuration;

        return $this;
    }

    /**
     * Get journalDuration
     *
     * @return integer 
     */
    public function getJournalDuration()
    {
        return $this->journalDuration;
    }

    /**
     * Set rentDuration
     *
     * @param integer $rentDuration
     * @return Flipper
     */
    public function setRentDuration($rentDuration)
    {
        $this->rentDuration = $rentDuration;

        return $this;
    }

    /**
     * Get rentDuration
     *
     * @return integer 
     */
    public function getRentDuration()
    {
        return $this->rentDuration;
    }

    /**
     * Set rentCost
     *
     * @param integer $rentCost
     * @return Flipper
     */
    public function setRentCost($rentCost)
    {
        $this->rentCost = $rentCost;

        return $this;
    }

    /**
     * Get rentCost
     *
     * @return integer 
     */
    public function getRentCost()
    {
        return $this->rentCost;
    }

    /**
     * Set countRentMess
     *
     * @param integer $countRentMess
     * @return Flipper
     */
    public function setCountRentMess($countRentMess)
    {
        $this->countRentMess = $countRentMess;

        return $this;
    }

    /**
     * Get countRentMess
     *
     * @return integer 
     */
    public function getCountRentMess()
    {
        return $this->countRentMess;
    }

    /**
     * Set activeAccLess
     *
     * @param integer $activeAccLess
     * @return Flipper
     */
    public function setActiveAccLess($activeAccLess)
    {
        $this->activeAccLess = $activeAccLess;

        return $this;
    }

    /**
     * Get activeAccLess
     *
     * @return integer 
     */
    public function getActiveAccLess()
    {
        return $this->activeAccLess;
    }

    /**
     * Set activeAccHint
     *
     * @param string $activeAccHint
     * @return Flipper
     */
    public function setActiveAccHint($activeAccHint)
    {
        $this->activeAccHint = $activeAccHint;

        return $this;
    }

    /**
     * Get activeAccHint
     *
     * @return string 
     */
    public function getActiveAccHint()
    {
        return $this->activeAccHint;
    }

    /**
     * Set messageBuyHint
     *
     * @param string $messageBuyHint
     * @return Flipper
     */
    public function setMessageBuyHint($messageBuyHint)
    {
        $this->messageBuyHint = $messageBuyHint;

        return $this;
    }

    /**
     * Get messageBuyHint
     *
     * @return string 
     */
    public function getMessageBuyHint()
    {
        return $this->messageBuyHint;
    }

    /**
     * Set radarCost
     *
     * @param integer $radarCost
     * @return Flipper
     */
    public function setRadarCost($radarCost)
    {
        $this->radarCost = $radarCost;

        return $this;
    }

    /**
     * Get radarCost
     *
     * @return integer 
     */
    public function getRadarCost()
    {
        return $this->radarCost;
    }

    /**
     * Set nextPointDistance
     *
     * @param integer $nextPointDistance
     * @return Flipper
     */
    public function setNextPointDistance($nextPointDistance)
    {
        $this->nextPointDistance = $nextPointDistance;

        return $this;
    }

    /**
     * Get nextPointDistance
     *
     * @return integer 
     */
    public function getNextPointDistance()
    {
        return $this->nextPointDistance;
    }

    /**
     * Set firstLeftBorder
     *
     * @param integer $firstLeftBorder
     * @return Flipper
     */
    public function setFirstLeftBorder($firstLeftBorder)
    {
        $this->firstLeftBorder = $firstLeftBorder;

        return $this;
    }

    /**
     * Get firstLeftBorder
     *
     * @return integer 
     */
    public function getFirstLeftBorder()
    {
        return $this->firstLeftBorder;
    }

    /**
     * Set secondLeftBorder
     *
     * @param integer $secondLeftBorder
     * @return Flipper
     */
    public function setSecondLeftBorder($secondLeftBorder)
    {
        $this->secondLeftBorder = $secondLeftBorder;

        return $this;
    }

    /**
     * Get secondLeftBorder
     *
     * @return integer 
     */
    public function getSecondLeftBorder()
    {
        return $this->secondLeftBorder;
    }

    /**
     * Set firstRightBorder
     *
     * @param integer $firstRightBorder
     * @return Flipper
     */
    public function setFirstRightBorder($firstRightBorder)
    {
        $this->firstRightBorder = $firstRightBorder;

        return $this;
    }

    /**
     * Get firstRightBorder
     *
     * @return integer 
     */
    public function getFirstRightBorder()
    {
        return $this->firstRightBorder;
    }

    /**
     * Set secondRightBorder
     *
     * @param integer $secondRightBorder
     * @return Flipper
     */
    public function setSecondRightBorder($secondRightBorder)
    {
        $this->secondRightBorder = $secondRightBorder;

        return $this;
    }

    /**
     * Get secondRightBorder
     *
     * @return integer 
     */
    public function getSecondRightBorder()
    {
        return $this->secondRightBorder;
    }

    /**
     * Set distanceHint
     *
     * @param string $distanceHint
     * @return Flipper
     */
    public function setDistanceHint($distanceHint)
    {
        $this->distanceHint = $distanceHint;

        return $this;
    }

    /**
     * Get distanceHint
     *
     * @return string 
     */
    public function getDistanceHint()
    {
        return $this->distanceHint;
    }

    /**
     * Set searchZoneCost
     *
     * @param integer $searchZoneCost
     * @return Flipper
     */
    public function setSearchZoneCost($searchZoneCost)
    {
        $this->searchZoneCost = $searchZoneCost;

        return $this;
    }

    /**
     * Get searchZoneCost
     *
     * @return integer 
     */
    public function getSearchZoneCost()
    {
        return $this->searchZoneCost;
    }

    /**
     * Set leftBorderSearchX
     *
     * @param integer $leftBorderSearchX
     * @return Flipper
     */
    public function setLeftBorderSearchX($leftBorderSearchX)
    {
        $this->leftBorderSearchX = $leftBorderSearchX;

        return $this;
    }

    /**
     * Get leftBorderSearchX
     *
     * @return integer 
     */
    public function getLeftBorderSearchX()
    {
        return $this->leftBorderSearchX;
    }

    /**
     * Set rightBorderSearchX
     *
     * @param integer $rightBorderSearchX
     * @return Flipper
     */
    public function setRightBorderSearchX($rightBorderSearchX)
    {
        $this->rightBorderSearchX = $rightBorderSearchX;

        return $this;
    }

    /**
     * Get rightBorderSearchX
     *
     * @return integer 
     */
    public function getRightBorderSearchX()
    {
        return $this->rightBorderSearchX;
    }

    /**
     * Set deltaSearchX
     *
     * @param integer $deltaSearchX
     * @return Flipper
     */
    public function setDeltaSearchX($deltaSearchX)
    {
        $this->deltaSearchX = $deltaSearchX;

        return $this;
    }

    /**
     * Get deltaSearchX
     *
     * @return integer 
     */
    public function getDeltaSearchX()
    {
        return $this->deltaSearchX;
    }

    /**
     * Set leftBorderSearchY
     *
     * @param integer $leftBorderSearchY
     * @return Flipper
     */
    public function setLeftBorderSearchY($leftBorderSearchY)
    {
        $this->leftBorderSearchY = $leftBorderSearchY;

        return $this;
    }

    /**
     * Get leftBorderSearchY
     *
     * @return integer 
     */
    public function getLeftBorderSearchY()
    {
        return $this->leftBorderSearchY;
    }

    /**
     * Set rightBorderSearchY
     *
     * @param integer $rightBorderSearchY
     * @return Flipper
     */
    public function setRightBorderSearchY($rightBorderSearchY)
    {
        $this->rightBorderSearchY = $rightBorderSearchY;

        return $this;
    }

    /**
     * Get rightBorderSearchY
     *
     * @return integer 
     */
    public function getRightBorderSearchY()
    {
        return $this->rightBorderSearchY;
    }

    /**
     * Set deltaSearchY
     *
     * @param integer $deltaSearchY
     * @return Flipper
     */
    public function setDeltaSearchY($deltaSearchY)
    {
        $this->deltaSearchY = $deltaSearchY;

        return $this;
    }

    /**
     * Get deltaSearchY
     *
     * @return integer 
     */
    public function getDeltaSearchY()
    {
        return $this->deltaSearchY;
    }

    /**
     * Set leftBorderSearchZ
     *
     * @param integer $leftBorderSearchZ
     * @return Flipper
     */
    public function setLeftBorderSearchZ($leftBorderSearchZ)
    {
        $this->leftBorderSearchZ = $leftBorderSearchZ;

        return $this;
    }

    /**
     * Get leftBorderSearchZ
     *
     * @return integer 
     */
    public function getLeftBorderSearchZ()
    {
        return $this->leftBorderSearchZ;
    }

    /**
     * Set rightBorderSearchZ
     *
     * @param integer $rightBorderSearchZ
     * @return Flipper
     */
    public function setRightBorderSearchZ($rightBorderSearchZ)
    {
        $this->rightBorderSearchZ = $rightBorderSearchZ;

        return $this;
    }

    /**
     * Get rightBorderSearchZ
     *
     * @return integer 
     */
    public function getRightBorderSearchZ()
    {
        return $this->rightBorderSearchZ;
    }

    /**
     * Set deltaSearchZ
     *
     * @param integer $deltaSearchZ
     * @return Flipper
     */
    public function setDeltaSearchZ($deltaSearchZ)
    {
        $this->deltaSearchZ = $deltaSearchZ;

        return $this;
    }

    /**
     * Get deltaSearchZ
     *
     * @return integer 
     */
    public function getDeltaSearchZ()
    {
        return $this->deltaSearchZ;
    }

    /**
     * Set incLeftSearchRadius
     *
     * @param integer $incLeftSearchRadius
     * @return Flipper
     */
    public function setIncLeftSearchRadius($incLeftSearchRadius)
    {
        $this->incLeftSearchRadius = $incLeftSearchRadius;

        return $this;
    }

    /**
     * Get incLeftSearchRadius
     *
     * @return integer 
     */
    public function getIncLeftSearchRadius()
    {
        return $this->incLeftSearchRadius;
    }

    /**
     * Set incRightSearchRadius
     *
     * @param integer $incRightSearchRadius
     * @return Flipper
     */
    public function setIncRightSearchRadius($incRightSearchRadius)
    {
        $this->incRightSearchRadius = $incRightSearchRadius;

        return $this;
    }

    /**
     * Get incRightSearchRadius
     *
     * @return integer 
     */
    public function getIncRightSearchRadius()
    {
        return $this->incRightSearchRadius;
    }

    /**
     * Set firstZoneDuration1
     *
     * @param integer $firstZoneDuration1
     * @return Flipper
     */
    public function setFirstZoneDuration1($firstZoneDuration1)
    {
        $this->firstZoneDuration1 = $firstZoneDuration1;

        return $this;
    }

    /**
     * Get firstZoneDuration1
     *
     * @return integer 
     */
    public function getFirstZoneDuration1()
    {
        return $this->firstZoneDuration1;
    }

    /**
     * Set firstZoneCost1
     *
     * @param integer $firstZoneCost1
     * @return Flipper
     */
    public function setFirstZoneCost1($firstZoneCost1)
    {
        $this->firstZoneCost1 = $firstZoneCost1;

        return $this;
    }

    /**
     * Get firstZoneCost1
     *
     * @return integer 
     */
    public function getFirstZoneCost1()
    {
        return $this->firstZoneCost1;
    }

    /**
     * Set firstZoneSpec1
     *
     * @param boolean $firstZoneSpec1
     * @return Flipper
     */
    public function setFirstZoneSpec1($firstZoneSpec1)
    {
        $this->firstZoneSpec1 = $firstZoneSpec1;

        return $this;
    }

    /**
     * Get firstZoneSpec1
     *
     * @return boolean 
     */
    public function getFirstZoneSpec1()
    {
        return $this->firstZoneSpec1;
    }

    /**
     * Set firstZoneDuration2
     *
     * @param integer $firstZoneDuration2
     * @return Flipper
     */
    public function setFirstZoneDuration2($firstZoneDuration2)
    {
        $this->firstZoneDuration2 = $firstZoneDuration2;

        return $this;
    }

    /**
     * Get firstZoneDuration2
     *
     * @return integer 
     */
    public function getFirstZoneDuration2()
    {
        return $this->firstZoneDuration2;
    }

    /**
     * Set firstZoneCost2
     *
     * @param integer $firstZoneCost2
     * @return Flipper
     */
    public function setFirstZoneCost2($firstZoneCost2)
    {
        $this->firstZoneCost2 = $firstZoneCost2;

        return $this;
    }

    /**
     * Get firstZoneCost2
     *
     * @return integer 
     */
    public function getFirstZoneCost2()
    {
        return $this->firstZoneCost2;
    }

    /**
     * Set firstZoneSpec2
     *
     * @param boolean $firstZoneSpec2
     * @return Flipper
     */
    public function setFirstZoneSpec2($firstZoneSpec2)
    {
        $this->firstZoneSpec2 = $firstZoneSpec2;

        return $this;
    }

    /**
     * Get firstZoneSpec2
     *
     * @return boolean 
     */
    public function getFirstZoneSpec2()
    {
        return $this->firstZoneSpec2;
    }

    /**
     * Set firstZoneDuration3
     *
     * @param integer $firstZoneDuration3
     * @return Flipper
     */
    public function setFirstZoneDuration3($firstZoneDuration3)
    {
        $this->firstZoneDuration3 = $firstZoneDuration3;

        return $this;
    }

    /**
     * Get firstZoneDuration3
     *
     * @return integer 
     */
    public function getFirstZoneDuration3()
    {
        return $this->firstZoneDuration3;
    }

    /**
     * Set firstZoneCost3
     *
     * @param integer $firstZoneCost3
     * @return Flipper
     */
    public function setFirstZoneCost3($firstZoneCost3)
    {
        $this->firstZoneCost3 = $firstZoneCost3;

        return $this;
    }

    /**
     * Get firstZoneCost3
     *
     * @return integer 
     */
    public function getFirstZoneCost3()
    {
        return $this->firstZoneCost3;
    }

    /**
     * Set firstZoneSpec3
     *
     * @param boolean $firstZoneSpec3
     * @return Flipper
     */
    public function setFirstZoneSpec3($firstZoneSpec3)
    {
        $this->firstZoneSpec3 = $firstZoneSpec3;

        return $this;
    }

    /**
     * Get firstZoneSpec3
     *
     * @return boolean 
     */
    public function getFirstZoneSpec3()
    {
        return $this->firstZoneSpec3;
    }

    /**
     * Set firstZoneDuration4
     *
     * @param integer $firstZoneDuration4
     * @return Flipper
     */
    public function setFirstZoneDuration4($firstZoneDuration4)
    {
        $this->firstZoneDuration4 = $firstZoneDuration4;

        return $this;
    }

    /**
     * Get firstZoneDuration4
     *
     * @return integer 
     */
    public function getFirstZoneDuration4()
    {
        return $this->firstZoneDuration4;
    }

    /**
     * Set firstZoneCost4
     *
     * @param integer $firstZoneCost4
     * @return Flipper
     */
    public function setFirstZoneCost4($firstZoneCost4)
    {
        $this->firstZoneCost4 = $firstZoneCost4;

        return $this;
    }

    /**
     * Get firstZoneCost4
     *
     * @return integer 
     */
    public function getFirstZoneCost4()
    {
        return $this->firstZoneCost4;
    }

    /**
     * Set firstZoneSpec4
     *
     * @param boolean $firstZoneSpec4
     * @return Flipper
     */
    public function setFirstZoneSpec4($firstZoneSpec4)
    {
        $this->firstZoneSpec4 = $firstZoneSpec4;

        return $this;
    }

    /**
     * Get firstZoneSpec4
     *
     * @return boolean 
     */
    public function getFirstZoneSpec4()
    {
        return $this->firstZoneSpec4;
    }

    /**
     * Set secondZoneDuration1
     *
     * @param integer $secondZoneDuration1
     * @return Flipper
     */
    public function setSecondZoneDuration1($secondZoneDuration1)
    {
        $this->secondZoneDuration1 = $secondZoneDuration1;

        return $this;
    }

    /**
     * Get secondZoneDuration1
     *
     * @return integer 
     */
    public function getSecondZoneDuration1()
    {
        return $this->secondZoneDuration1;
    }

    /**
     * Set secondZoneCost1
     *
     * @param integer $secondZoneCost1
     * @return Flipper
     */
    public function setSecondZoneCost1($secondZoneCost1)
    {
        $this->secondZoneCost1 = $secondZoneCost1;

        return $this;
    }

    /**
     * Get secondZoneCost1
     *
     * @return integer 
     */
    public function getSecondZoneCost1()
    {
        return $this->secondZoneCost1;
    }

    /**
     * Set secondZoneSpec1
     *
     * @param boolean $secondZoneSpec1
     * @return Flipper
     */
    public function setSecondZoneSpec1($secondZoneSpec1)
    {
        $this->secondZoneSpec1 = $secondZoneSpec1;

        return $this;
    }

    /**
     * Get secondZoneSpec1
     *
     * @return boolean 
     */
    public function getSecondZoneSpec1()
    {
        return $this->secondZoneSpec1;
    }

    /**
     * Set secondZoneDuration2
     *
     * @param integer $secondZoneDuration2
     * @return Flipper
     */
    public function setSecondZoneDuration2($secondZoneDuration2)
    {
        $this->secondZoneDuration2 = $secondZoneDuration2;

        return $this;
    }

    /**
     * Get secondZoneDuration2
     *
     * @return integer 
     */
    public function getSecondZoneDuration2()
    {
        return $this->secondZoneDuration2;
    }

    /**
     * Set secondZoneCost2
     *
     * @param integer $secondZoneCost2
     * @return Flipper
     */
    public function setSecondZoneCost2($secondZoneCost2)
    {
        $this->secondZoneCost2 = $secondZoneCost2;

        return $this;
    }

    /**
     * Get secondZoneCost2
     *
     * @return integer 
     */
    public function getSecondZoneCost2()
    {
        return $this->secondZoneCost2;
    }

    /**
     * Set secondZoneSpec2
     *
     * @param boolean $secondZoneSpec2
     * @return Flipper
     */
    public function setSecondZoneSpec2($secondZoneSpec2)
    {
        $this->secondZoneSpec2 = $secondZoneSpec2;

        return $this;
    }

    /**
     * Get secondZoneSpec2
     *
     * @return boolean 
     */
    public function getSecondZoneSpec2()
    {
        return $this->secondZoneSpec2;
    }

    /**
     * Set secondZoneDuration3
     *
     * @param integer $secondZoneDuration3
     * @return Flipper
     */
    public function setSecondZoneDuration3($secondZoneDuration3)
    {
        $this->secondZoneDuration3 = $secondZoneDuration3;

        return $this;
    }

    /**
     * Get secondZoneDuration3
     *
     * @return integer 
     */
    public function getSecondZoneDuration3()
    {
        return $this->secondZoneDuration3;
    }

    /**
     * Set secondZoneCost3
     *
     * @param integer $secondZoneCost3
     * @return Flipper
     */
    public function setSecondZoneCost3($secondZoneCost3)
    {
        $this->secondZoneCost3 = $secondZoneCost3;

        return $this;
    }

    /**
     * Get secondZoneCost3
     *
     * @return integer 
     */
    public function getSecondZoneCost3()
    {
        return $this->secondZoneCost3;
    }

    /**
     * Set secondZoneSpec3
     *
     * @param boolean $secondZoneSpec3
     * @return Flipper
     */
    public function setSecondZoneSpec3($secondZoneSpec3)
    {
        $this->secondZoneSpec3 = $secondZoneSpec3;

        return $this;
    }

    /**
     * Get secondZoneSpec3
     *
     * @return boolean 
     */
    public function getSecondZoneSpec3()
    {
        return $this->secondZoneSpec3;
    }

    /**
     * Set secondZoneDuration4
     *
     * @param integer $secondZoneDuration4
     * @return Flipper
     */
    public function setSecondZoneDuration4($secondZoneDuration4)
    {
        $this->secondZoneDuration4 = $secondZoneDuration4;

        return $this;
    }

    /**
     * Get secondZoneDuration4
     *
     * @return integer 
     */
    public function getSecondZoneDuration4()
    {
        return $this->secondZoneDuration4;
    }

    /**
     * Set secondZoneCost4
     *
     * @param integer $secondZoneCost4
     * @return Flipper
     */
    public function setSecondZoneCost4($secondZoneCost4)
    {
        $this->secondZoneCost4 = $secondZoneCost4;

        return $this;
    }

    /**
     * Get secondZoneCost4
     *
     * @return integer 
     */
    public function getSecondZoneCost4()
    {
        return $this->secondZoneCost4;
    }

    /**
     * Set secondZoneSpec4
     *
     * @param boolean $secondZoneSpec4
     * @return Flipper
     */
    public function setSecondZoneSpec4($secondZoneSpec4)
    {
        $this->secondZoneSpec4 = $secondZoneSpec4;

        return $this;
    }

    /**
     * Get secondZoneSpec4
     *
     * @return boolean 
     */
    public function getSecondZoneSpec4()
    {
        return $this->secondZoneSpec4;
    }

}
