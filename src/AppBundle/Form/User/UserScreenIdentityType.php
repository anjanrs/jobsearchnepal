<?php
namespace AppBundle\Form\User;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ScreenIdentityType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('uploadedFile','file')
            ->add('screen_name','text');
    }

    public function getName(){
        return 'screenidentity';
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\UserProfile',
           'validation_groups' => array('screenidentity'),
        ));
    }

}