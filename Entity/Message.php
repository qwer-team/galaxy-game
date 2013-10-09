<?php

namespace Galaxy\GameBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Message
 */
class Message {

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $image1;

    /**
     * @var string
     */
    private $image2;

    /**
     * @var string
     */
    private $image3;

    /**
     * @var string
     */
    private $text;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    public function getImage1() {
        return $this->image1;
    }

    public function setImage1($image1) {
        $this->image1 = $image1;
    }

    public function getImage2() {
        return $this->image2;
    }

    public function setImage2($image2) {
        $this->image2 = $image2;
    }

    public function getImage3() {
        return $this->image3;
    }

    public function setImage3($image3) {
        $this->image3 = $image3;
    }

    /**
     * Set text
     *
     * @param string $text
     * @return Message
     */
    public function setText($text) {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string 
     */
    public function getText() {
        return $this->text;
    }

}
