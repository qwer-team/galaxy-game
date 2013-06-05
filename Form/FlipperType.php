<?php

namespace Galaxy\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FlipperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('maxJump', 'integer')
            ->add('costJump', 'integer')
            ->add('impossibleJumpHint', 'textarea')
            ->add('paymentFromDeposit', 'checkbox', array("required" => false))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Galaxy\GameBundle\Entity\Flipper',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return '';
    }
}
