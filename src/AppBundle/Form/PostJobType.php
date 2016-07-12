<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Doctrine\ORM\EntityRepository;

class PostJobType extends AbstractType
{
    private $securityContext;
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('description','textarea')
            ->add('validTill','text')
            ->add('jobType','entity', [
                    'class' => 'AppBundle:JobType',
                    'choice_label' => 'job_type',
                    'required' => true,
            ])
            ->add('rate','text',[
              'required' => false,
            ])
            ->add('rateType', 'entity', [
                'class'    => 'AppBundle:RateType',
                'choice_label' => 'rate_type',
                'required' => false,
            ])
            ->add('location')
            ->add('jobCategory', 'entity', [
                'class'    => 'AppBundle:JobCategory',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('jc')->orderBy('jc.title', 'ASC');
                },
                'choice_label' => 'title',
                'required' => true,
            ])


        ;
    }

    public function getName()
    {
        return 'postjob';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Job',
            'validation_groups' => array('postjob'),
        ));
    }

    public function setSecurityContext($securityContext){
        $this->securityContext = $securityContext;
    }
}