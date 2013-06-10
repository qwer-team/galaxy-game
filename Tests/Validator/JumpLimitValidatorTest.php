<?php

namespace Galaxy\GameBundle\Tests\Validator;

use Galaxy\GameBundle\Validator\JumpLimit;
use Galaxy\GameBundle\Validator\JumpLimitValidator;
use Galaxy\GameBundle\Entity\Jump;

class JumpLimitValidatorTest extends AbstractJumpValidatorTest
{
    /**
     *
     * @var \Galaxy\GameBundle\Validator\JumpLimitValidator
     */
    private $validator;
    
    /**
     *
     * @var \Galaxy\GameBundle\Validator\JumpLimit
     */
    private $constraint;
    
    
     protected function setUp()
    {
        $this->validator = new JumpLimitValidator();
        $this->constraint = new JumpLimit();

        $context = $this->getContextMock();
        $this->validator->initialize($context);
    }
    
    public function dataProvider()
    {
        return array(
            array(1, 1, 2, false,  0, 30, 0),
            array(10, 90, 9, true, 0, 30 , 1),
            array(10, 90, 9, true, 1, 30 , 0),
            array(12, 89, 10, false,  0,  30, 1),
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidator($x, $y, $z, $superJump, $superJumps, $maxJump, $count)
    {
        $jump = new Jump();
        $jump->setX($x);
        $jump->setY($y);
        $jump->setZ($z);
        $jump->setUserId(1);
        $jump->setSuperjump($superJump);
        
        $this->maxJump = $maxJump;
        $this->superJumps = $superJumps;
        
        $this->validator->setUserRepo($this->getUserRepoMock());
        $this->validator->validate($jump, $this->constraint);
        $this->assertEquals($count, count($this->violations));
        
    }
}