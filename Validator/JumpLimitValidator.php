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
        $dx = $x - $userInfo->getX();
        $dy = $y - $userInfo->getY();
        $dz = $z - $userInfo->getZ();
        $userJump = sqrt(pow($dx, 2) + pow($dy, 2) + pow($dz, 2));
        if ($flipperMaxJump < $userJump && $userInfo->getSuperJumps() == 0) {
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