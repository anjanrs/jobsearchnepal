<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserAddress;
use AppBundle\Entity\Job;
use AppBundle\Entity\JobApplication;
use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Form\UserRegistrationType;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Form\FormError;


class EmployerController extends Controller
{
    /**
     * @Route("/emp/dashboard", name="emp-dashboard")
     * @Template()
     */
    public function showDashBoardAction()
    {
        return array(
                // ...
            );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("emp/jobsposted", name="emp-jobsposted")
     * @Template
     */
    public function showJobsPostedAction()
    {
        $user = $this->getUser();
        $job_manager = $this->get("jsn.manager.job");
        $jobs_posted = $job_manager->getAllJobsPosted($user);
        return array('jobsPosted' => $jobs_posted);
    }

      /**
     * @param Job $job
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("emp/job/detail/{id}", name="emp-job-detail")
     * @ParamConverter("job", class="AppBundle:Job")
     * @Template
     */
    public function empJobDetailAction(Job $job)
    {
        $this->denyAccessUnlessGranted('view', $job, 'Unauthorized access!');
        return array('job' => $job);
    }


    /**
     * @Route("/post/job", name="post-job")
     */
    public function postJobAction(Request $request)
    {
        $errors = array();
        $job = new Job();
        $user = $this->getUser();
        $blnIsEmployerLoggedIn = $this->isGranted('IS_AUTHENTICATED_REMEMBERED') && $this->isGranted('ROLE_EMPLOYER');
        $blnIsAnonymous = $this->isGranted('IS_AUTHENTICATED_ANONYMOUSLY') && !$this->isGranted('IS_AUTHENTICATED_REMEMBERED');
        if ( !($blnIsEmployerLoggedIn || $blnIsAnonymous)) {
            throw $this->createAccessDeniedException();
        }
        $form = $this->createForm('postjob',$job)
            ->add('post_job', 'submit', array('label' => 'Post Job'));
        if (!$this->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            $form
                ->add('sign_in', 'choice', array(
                    'mapped' => false,
                    'choices'  => array('sign-in' => 'Sign In', 'register' => 'Register'),
                    'required' => true,
                    'expanded' => true,
                    'multiple' => false,
                    'empty_data' => 'sign-in',
                    'data' => 'sign-in',
                ))
                ->add('sign_in_email', 'text', array('mapped' => false,'required' => false))
                ->add('sign_in_password', 'password', array('mapped' => false,'required' => false))
                ->add('register', new UserRegistrationType(), array('mapped' => false, 'required' => false));
        }
        $form->handleRequest($request);
        $job = $form->getData();
        if ($form->isValid()) {
            if ($form->has('sign_in')) {
                $user_manager = $this->get("jsn.manager.user");
                $sign_in_type = $form->get('sign_in')->getData();
                if ($sign_in_type == "sign-in") {
                    $email = $form->get('sign_in_email')->getData();
                    $password = $form->get('sign_in_password')->getData();
                    if(empty($email)) {
                        $errors[] = new FormError("Email is empty");
                    }
                    elseif(empty($password)) {
                        $errors[] = new FormError("Password is empty");
                    }
                    else {
                        $user = $user_manager->checkEmployerLogin($email, $password);
                    }
                } else {
                    $register_user = $form->get('register')->getData();
                    $validator = $this->get("validator");
                    $errors = $validator->validate($register_user,"registration");
                    if(count($errors) == 0) {
                        $user = $register_user;
                        $user_manager->registerUser($user, "employer");
                    }
                }
                if (isset($user)) {
                    $token = new UsernamePasswordToken($user, NULL, 'main', $user->getRoles());
                    $this->get("security.context")->setToken($token);
                }
            }
            if(count($errors)==0 && !isset($user)) {
                $errors[] = new FormError("Invalid Credential");
            } elseif ($this->isGranted('ROLE_EMPLOYER')) {
                $job->setEmployer($user);
                $job->setValidTill(strtotime($job->getValidTill()));
                $job_manager = $this->get("jsn.manager.job");
                $job_manager->postJob($job);
                return $this->redirectToRoute('search-jobs', array(), 301);
            }
        }
        return $this->render('AppBundle:Employer:postJob.html.twig', array(
            'form' => $form->createView(),
            'registration_errors' => $errors,
        ));
    }

    /**
     * @Route("emp/job/{id}/applications", name="emp-job-applications")
     * @ParamConverter("job", class="AppBundle:Job")
     * @Template
     */
    public function listApplicationsAction(Job $job)
    {
        $this->denyAccessUnlessGranted('view', $job, 'Unauthorized access!');
        $applications = $this->get("jsn.manager.job")->getJobApplications($job);
        return array("applications" => $applications);
    }

    /**
     * @Route("emp/job/application/{id}", name="emp-job-application-detail")
     * @ParamConverter("application", class="AppBundle:JobApplication")
     * @Template
     */
    public function showApplicationDetailAction(Request $request, JobApplication $application) 
    {
        $this->denyAccessUnlessGranted('empview', $application, 'Unauthorized access!');
        $message = new Message();
        $message->setFromUser($this->getUser());
        $message->setToUser($application->getUser());
        $message->setMessageDateTime(time());
        $message->setJobApplication($application);

        $form = $this->createForm('message', $message)
            ->add('send_message', 'submit', array('label' => 'Send'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $message = $form->getData();
            $this->get("jsn.manager.message")->createMessage($message);
        }
        return array(
            "form" => $form->createView(),
            "application" => $application
        );
    }
}
