<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class UserScreenIdentityType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('uploadedFile','file')
            ->add('screen_name','text');
    }

    public function getName(){
        return 'userscreenidentity';
    }

    public function configureOptions(OptionsResolver $resolver){
        $resolver->setDefaults(array(
           'data_class' => 'AppBundle\Entity\UserProfile',
           'validation_groups' => array('screenidentity'),
        ));
    }

}