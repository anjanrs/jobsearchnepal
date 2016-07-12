<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phoneNo')
            ->add('coverLetter','textarea')
            ->add('uploadedFile','file');
//            ->add('userResume', 'entity', [
//                'class'    => 'AppBundle:UserResume',
//                'choice_label' => 'resume_file_name',
//                'required' => false,
//                'multiple' => false,
//            ]);

    }

    public function getName()
    {
        return 'jobapplication';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\JobApplication',
            'validation_groups' => array('jobapplication')
        ));
    }
}