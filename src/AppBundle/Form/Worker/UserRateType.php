<?php

namespace AppBundle\Form\Worker;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class UserRateType extends AbstractType
{
    public function getName() {
        return 'userrate';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rateType','entity',array(
                'class'=> 'AppBundle:RateType',
                'choice_label' => 'rateType'
                ))
            ->add('rate','text');

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\UserRate",
            "validation_groups" => array("userrate")
        ));
    }
}