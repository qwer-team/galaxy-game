<?php

namespace Galaxy\GameBundle\Tests\Validator;

use Galaxy\GameBundle\Entity\Flipper;
use Galaxy\GameBundle\Entity\UserInfo;

abstract class AbstractJumpValidatorTest extends \PHPUnit_Framework_TestCase
{

    protected $violations = array();
    protected $active;
    protected $deposite;
    protected $jumpCost;
    protected $maxJump;
    protected $paymentFromDeposit;
    protected $superJumps;

    protected function getContextMock()
    {
        $mock = $this->getMockBuilder("Symfony\Component\Validator\ExecutionContextInterface")
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->any())
                ->method("addViolation")
                ->withAnyParameters()
                ->will($this->returnCallback(array($this, 'violationCallback')));

        return $mock;
    }

    public function violationCallback($message)
    {
        $this->violations[] = $message;
    }

    protected function getDocsRemoteMock()
    {
        $mock = $this->getMockBuilder("Galaxy\GameBundle\Service\DocumentsRemoteService")
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->any())
                ->method("getFunds")
                ->withAnyParameters()
                ->will($this->returnCallback(array($this, 'getFundsCallback')));
        return $mock;
    }

    public function getFundsCallback()
    {
        $funds = array(
            "active" => $this->active,
            "deposite" => $this->deposite,
            "safe" => 10,
        );

        return (object) $funds;
    }

    protected function getUserRepoMock()
    {
        $mock = $this->getMockBuilder("Galaxy\GameBundle\Repository\UserInfoRepository")
                ->disableOriginalConstructor()
                ->getMock();

        $mock->expects($this->any())
                ->method("getFlipper")
                ->withAnyParameters()
                ->will($this->returnCallback(array($this, 'getFlipperCallback')));

        $mock->expects($this->any())
                ->method("findOneBy")
                ->withAnyParameters()
                ->will($this->returnCallback(array($this, 'getUserInfoCallback')));
        return $mock;
    }

    public function getFlipperCallback()
    {
        $flipper = new Flipper;
        $flipper->setCostJump($this->jumpCost);
        $flipper->setMaxJump($this->maxJump);
        $flipper->setPaymentFromDeposit($this->paymentFromDeposit);

        return $flipper;
    }
    
    public function getUserInfoCallback()
    {
        $userInfo = new UserInfo;
        $userInfo->setX(1);
        $userInfo->setY(1);
        $userInfo->setZ(1);
        $userInfo->setSuperJumps($this->superJumps);
        
        return $userInfo;
    }

}