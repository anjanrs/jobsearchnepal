<?php
namespace AppBundle\Controller;

use AppBundle\Entity\JobApplication;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Job;
use AppBundle\Entity\UserResume;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class JobController extends Controller
{
    /**
    * @Route("/search/jobs", name="search-jobs")
    */
    public function searchJobsAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder()
            ->add('searchKey','text', array('required' => false))
            ->add('jobCategory','entity', [
                'class' => 'AppBundle:JobCategory',
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'Any',
            ])
            ->add('location', 'text', array('required'=> false))
            ->add('jobType', 'entity', [
                'class' => 'AppBundle:JobType',
                'choice_label' => 'job_type',
                'required' => false,
                'placeholder' => 'Any',
            ])
            ->add('dateRange', 'choice', array(
                'choices' => array(
                    'today' => 'Today',
                    'last3days' => 'Last 3 days',
                    'last7days' => 'Last 7 days',
                    'last14days' => 'Last 14 days',
                    'last30days' => 'Last 30 days',
                 ),
                'placeholder' => 'Any',
                'required' => false,
             ))
            ->add('btnsearch','submit',array('label'=>'Search'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
        }

        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Job");
        $jobs = $repository->searchJobs($data);
        #$categories = $repository->getCategoryFacet($searchkey);

        return $this->render('AppBundle:Job:searchJobs.html.twig', array(
            'jobs' => $jobs,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/job/detail/{jobid}", name="job-detail")
     * @Template
     */
    public function jobDetailAction($jobid)
    {
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Job");
        $job = $repository->find($jobid);
        return array('job' => $job);
    }
  
}

