<?php

namespace Galaxy\GameBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class JumpLimitValidator extends ConstraintValidator
{

    /**
     *
     * @var \Doctrine\ORM\EntityManager 
     */
    private $em;

    /**
     *
     * @var \Galaxy\GameBundle\Resources\UserInfoRepository 
     */
    private $userRepo;

    /**
     *
     * @var \Galaxy\GameBundle\Entity\Jump
     */
    private $jump;

    public function validate($value, Constraint $constraint)
    {
        $this->jump = $value;
        $userId = $this->jump->getUserId();
        $x = $this->jump->getX();
        $y = $this->jump->getY();
        $z = $this->jump->getZ();
        $userInfo = $this->userRepo->findOneBy(
                array(
                    'userId' => $userId,
                )
        );

        $flipper = $this->getFlipper($userId);
        $flipperMaxJump = $flipper->getMaxJump();
        $dx = $userInfo->getX() - $x;
        $dy = $userInfo->getY() - $y;
        $dz = $userInfo->getZ() - $z;
        
        $distance1 = sqrt(pow($dx, 2) + pow($dy, 2) + pow($dz, 2));
        
        $distance2 = sqrt(pow(999 - abs($dx), 2) + pow($dy, 2) + pow($dz, 2));
        $distance3 = sqrt(pow($dx, 2) + pow(999 - abs($dy), 2) + pow($dz, 2));
        $distance4 = sqrt(pow($dx, 2) + pow($dy, 2) + pow(999 - abs($dz), 2));
        
        $distance5 = sqrt(pow(999 - abs($dx), 2) + pow(999 - abs($dy), 2) + pow($dz, 2));
        $distance6 = sqrt(pow(999 - abs($dx), 2) + pow($dy, 2) + pow(999 - abs($dz), 2));
        $distance7 = sqrt(pow($dx, 2) + pow(999 - abs($dy), 2) + pow(999 - abs($dz), 2));
        
        $distance8 = sqrt(pow(999 - abs($dx), 2) + pow(999 - abs($dy), 2) + pow(999 - abs($dz), 2));
        $userJump = min($distance1, $distance2, $distance3, $distance4, $distance5, $distance6, $distance7, $distance8);
        if ($flipperMaxJump < $userJump && $userInfo->getSuperJumps() <= 0) {
            $this->context->addViolation($constraint->getMessage());
        }
    }

    public function setEntityManager($em)
    {
        $namespace = "GalaxyGameBundle:UserInfo";
        $this->em = $em;
        $this->userRepo = $this->em->getRepository($namespace);
    }

     public function setUserRepo($repo)
    {
        $this->userRepo = $repo;
    }
    
    /**
     * 
     * @param integer $userId
     * @return \Galaxy\GameBundle\Entity\Flipper
     */
    private function getFlipper($userId)
    {
        return $this->userRepo->getFlipper($userId);
    }
    

}