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
    private $questionId;

    /**
     * @var integer
     */
    private $answerId;

    /**
     * @var integer
     */
    private $status = 1;

    /**
     * @var integer 
     */
    private $messageId;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Galaxy\GameBundle\Entity\UserInfo
     */
    private $userInfo;

    /**
     * Set questionId
     *
     * @param integer $questionId
     * @return Question
     */
    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;

        return $this;
    }

    /**
     * Get questionId
     *
     * @return integer 
     */
    public function getQuestionId()
    {
        return $this->questionId;
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

    public function getMessageId()
    {
        return $this->messageId;
    }

    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    public function getAnswerId()
    {
        return $this->answerId;
    }

    public function setAnswerId($answerId)
    {
        $this->answerId = $answerId;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

}
