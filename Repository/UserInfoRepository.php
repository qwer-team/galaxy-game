<?php

namespace Galaxy\GameBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserInfoRepository extends EntityRepository
{
    public function getFlipper($userId){
        $qb = $this->createQueryBuilder("user");
        $qb->select("user, flip")
           ->innerJoin("user.flipper", "flip")
           ->where("user.userId = :userId")
           ->setParameter("userId", $userId);
       
        $user = $qb->getQuery()->getOneOrNullResult();
        $flipper = $user->getFlipper();
        
        return $flipper;
    }
}