<?php

namespace Galaxy\GameBundle\Tests\Validator;

use Galaxy\GameBundle\Validator\FundsLimitValidator;
use Galaxy\GameBundle\Validator\FundsLimit;
use Galaxy\GameBundle\Entity\Jump;

class FundsLimitValidatorTest extends AbstractJumpValidatorTest
{

    /**
     * @var \Galaxy\GameBundle\Validator\FundsLimitValidator
     */
    protected $validator;

    /**
     *
     * @var \Galaxy\GameBundle\Validator\FundsLimit
     */
    protected $constraint;

    protected function setUp()
    {
        $this->validator = new FundsLimitValidator();
        $this->constraint = new FundsLimit();

        $context = $this->getContextMock();
        $this->validator->initialize($context);
    }

    public function dataProvider()
    {
        return array(
            //paymentFromDeposite, active, deposite, jumpCost, maxJump, count
            array(false, 10, 10, 5, 30, 0),
            array(false, 2, 10, 5, 30, 1),
            array(true, 2, 10, 5, 30, 0),
            array(true, 40, -10, 10, 5, 1),
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidator($paymentFromDeposite, $active, $deposite, $jumpCost, $maxJump, $count)
    {
        $jump = new Jump();
        $jump->setX(1);
        $jump->setY(1);
        $jump->setZ(1);
        $jump->setUserId(1);
        $jump->setSuperjump(true);

        $this->active = $active;
        $this->deposite = $deposite;
        $this->jumpCost = $jumpCost;
        $this->maxJump = $maxJump;
        $this->paymentFromDeposit = $paymentFromDeposite;

        $this->validator->setDocsRemote($this->getDocsRemoteMock());
        $this->validator->setUserRepo($this->getUserRepoMock());

        $this->validator->validate($jump, $this->constraint);

        $this->assertEquals($count, count($this->violations));
    }

}