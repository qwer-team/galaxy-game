<?php

namespace Galaxy\GameBundle\Validator;

use Symfony\Component\Validator\Constraint;

class JumpLimit extends Constraint
{
   public $message = "jump limit";
    
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
    
    public function validatedBy()
    {
        return "jump_limit";
    }

    public function getMessage()
    {
        return $this->message;
    }
}