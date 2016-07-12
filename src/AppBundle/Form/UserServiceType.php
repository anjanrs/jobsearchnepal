<?php
namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("title")
            ->add("description", "textarea")
            ->add("rate")
            ->add("rateType", 'entity',[
                'class' => 'AppBundle:RateType',
                'choice_label' => 'rate_type',
                'required' => false
            ])
            ->add("minimumBudget")
            ->add("jobCategory","entity",[
                'class' => 'AppBundle:JobCategory',
                'choice_label' => 'title',
            ]);
    }

    public function getName()
    {
        return 'userservice';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "data_class" => "AppBundle\Entity\UserService",
            "validation_groups" => array("userservice")
        ));
    }
}