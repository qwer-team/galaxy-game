<?php

namespace Galaxy\GameBundle\Validator;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class FundsLimitValidator extends ConstraintValidator
{

    /**
     *
     * @var \Galaxy\GameBundle\Entity\Jump
     */
    private $jump;

    /**
     *
     * @var \Galaxy\GameBundle\Service\DocumentsRemoteService 
     */
    private $docsRemote;

    /**
     *
     * @var \Galaxy\GameBundle\Resources\UserInfoRepository 
     */
    private $userRepo;

    public function validate($value, Constraint $constraint)
    {
        $this->jump = $value;
        $userId = $this->jump->getUserId();
        $funds = $this->docsRemote->getFunds($userId);
        $available = 0;

        $flipper = $this->getFlipper($userId);
        if ($flipper->getPaymentFromDeposit()) {
            $available = $funds->deposite;
        } else {
            $available = $funds->active;
        }

        if ($available < $flipper->getCostJump()) {
            $this->context->addViolation($constraint->getMessage());
        }
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

    public function setDocsRemote($docsRemote)
    {
        $this->docsRemote = $docsRemote;
    }

    public function setUserRepo($repo)
    {
        $this->userRepo = $repo;
    }

    public function setEntityManager($em)
    {
        $this->userRepo = $em->getRepository("GalaxyGameBundle:UserInfo");
    }

}