<?php
namespace AppBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\User\UserProfileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UserContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', 'text')
            ->add('lastname', 'text')
            ->add('email', 'text')
            ->add('userprofile', new UserProfileType(), array('validation_groups'=>array('personalinfo')))
        ;
    }

    public function getName()
    {
        return 'usercontact';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'validation_groups' => array('personalinfo'),
            'cascade_validation'=>true,
        ));
    }
}