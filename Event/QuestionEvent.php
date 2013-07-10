<?php

namespace Galaxy\GameBundle\Event;

use \Symfony\Component\EventDispatcher\Event;

class QuestionEvent extends Event
{

    private $questionId;

    function __construct($questionId)
    {
        $this->questionId = $questionId;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

}