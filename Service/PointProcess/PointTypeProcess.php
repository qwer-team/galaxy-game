<?php

namespace Galaxy\GameBundle\Service\PointProcess;


interface PointTypeProcess
{
    public function proceed($response, $userId);
}
