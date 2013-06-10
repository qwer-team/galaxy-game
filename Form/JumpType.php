<?php

namespace Galaxy\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JumpType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('x', 'integer')
                ->add('y', 'integer')
                ->add('z', 'integer')
                ->add('superjump', 'checkbox', array("required" => false))
                ->add('userId', 'integer')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Galaxy\GameBundle\Entity\Jump',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return '';
    }

}