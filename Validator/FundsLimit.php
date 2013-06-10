<?php

namespace Galaxy\GameBundle\Validator;

use Symfony\Component\Validator\Constraint;

class FundsLimit extends Constraint
{
   public $message = "funds limit";
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    
    public function validatedBy()
    {
        return "funds_limit";
    }

    public function getMessage()
    {
        return $this->message;
    }
}