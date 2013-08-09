<?php

namespace Galaxy\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 */
class Question
{

    /**
     * @var integer
     */
    private $rightAnswer;

    /**
     * @var integer
     */
    private $messageId;

    /**
     * @var integer
     */
    private $status = 1;

    /**
     * @var array
     */
    private $answers;

    /**
     * @var integer
     */
    private $jumpsToQuestion;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Galaxy\GameBundle\Entity\UserInfo
     */
    private $userInfo;

    /**
     * @var integer
     */
    private $seconds;

    /**
     * Set rightAnswer
     *
     * @param integer $rightAnswer
     * @return Question
     */
    public function setRightAnswer($rightAnswer)
    {
        $this->rightAnswer = $rightAnswer;

        return $this;
    }

    /**
     * Get rightAnswer
     *
     * @return integer 
     */
    public function getRightAnswer()
    {
        return $this->rightAnswer;
    }

    /**
     * Set messageId
     *
     * @param integer $messageId
     * @return Question
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;

        return $this;
    }

    /**
     * Get messageId
     *
     * @return integer 
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * Set seconds
     *
     * @param integer $seconds
     * @return Question
     */
    public function setSeconds($seconds)
    {
        $this->seconds = $seconds;

        return $this;
    }

    /**
     * Get seconds
     *
     * @return integer 
     */
    public function getSeconds()
    {
        return $this->seconds;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Question
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set answers
     *
     * @param array $answers
     * @return Question
     */
    public function setAnswers($answers)
    {
        $this->answers = $answers;

        return $this;
    }

    /**
     * Get answers
     *
     * @return array 
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set jumpsToQuestion
     *
     * @param integer $jumpsToQuestion
     * @return Question
     */
    public function setJumpsToQuestion($jumpsToQuestion)
    {
        $this->jumpsToQuestion = $jumpsToQuestion;

        return $this;
    }

    /**
     * Get jumpsToQuestion
     *
     * @return integer 
     */
    public function getJumpsToQuestion()
    {
        return $this->jumpsToQuestion;
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

    /**
     * Set userInfo
     *
     * @param \Galaxy\GameBundle\Entity\UserInfo $userInfo
     * @return Question
     */
    public function setUserInfo(\Galaxy\GameBundle\Entity\UserInfo $userInfo)
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    /**
     * Get userInfo
     *
     * @return \Galaxy\GameBundle\Entity\UserInfo 
     */
    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function subJumpsToQuestion()
    {
        $this->jumpsToQuestion--;
    }

    /**
     * @var string
     */
    private $text;


    /**
     * Set text
     *
     * @param string $text
     * @return Question
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText()
    {
        return $this->text;
    }
    /**
     * @var \DateTime
     */
    private $started;


    /**
     * Set started
     *
     * @param \DateTime $started
     * @return Question
     */
    public function setStarted($started)
    {
        $this->started = $started;

        return $this;
    }

    /**
     * Get started
     *
     * @return \DateTime 
     */
    public function getStarted()
    {
        return $this->started;
    }
}
