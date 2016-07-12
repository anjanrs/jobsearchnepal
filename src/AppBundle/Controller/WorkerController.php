<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserRate;
use AppBundle\Entity\UserResume;
use AppBundle\Entity\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Job;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Entity\JobApplication;

class WorkerController extends Controller
{

    /**
     * @Route("profile/resume", name="prf-resume")
     * @Template
     */
    public function addResumeAction(Request $request)
    {
        $user = $this->getUser();
        $resumes = $user->getUserResumes();
        $resume = new UserResume();
        $form = $this->createForm('userresume', $resume);
        $form->add('upload','button', array('label'=>'Add a Resume'));
//        $form->add('addresume', 'submit', array('label'=>'Add'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $resume = $form->getData();
            $resume->setUser($user);
            $this->get('jsn.manager.user')->addResume($resume);
        }

        return array(
            'form' => $form->createView(),
            'resumes'=> $resumes,
        );
    }

    /**
     * @param UserResume $resume
     * @return Response
     * @Route("/download/resume/{id}", name="download-resume")
     * @ParamConverter("resume", class="AppBundle:UserResume")
     */
    public function downloadResumeAction(UserResume $resume)
    {
        $this->denyAccessUnlessGranted('download', $resume, 'Unauthorized access!');
        $filename = $resume->getAbsolutePath($resume->getResumeFileName());
        $response = new Response();
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '";');
        $response->headers->set('Content-length', filesize($filename));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(file_get_contents($filename));
        return $response;
    }

    /**
     * @param UserResume $resume
     * @Route("/remove/resume/{id}", name="remove-resume")
     * @ParamConverter("resume", class="AppBundle:UserResume")
     */
    public function removeResumeAction(UserResume $resume)
    {
        $this->denyAccessUnlessGranted('remove', $resume, 'Unauthorized access!');
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($resume);
        $em->flush();
        return $this->redirectToRoute('prf-resume', array(), 301);
    }

    /**
     * @param UserResume $resume
     * @Route("/prefer/resume/{id}", name="prefer-resume")
     * @ParamConverter("resume", class="AppBundle:UserResume")
     */
    public function preferResumeAction(UserResume $resume)
    {
        $this->denyAccessUnlessGranted('prefer', $resume, 'Unauthorized access!');
        $user = $resume->getUser();
        $user_resumes = $user->getUserResumes();
        foreach ($user_resumes as $key => $user_resume) {
            if ($resume->getId() == $user_resume->getId()) {
                $user_resume->setPreferred(1);
            } else {
                $user_resume->setPreferred(0);
            }
        }
        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('prf-resume', array(), 301);
    }

    /**
     * @Route("/profile/services", name="list-worker-services")
     * @Template
     */
    public function listServicesAction(){
        $user = $this->getUser();
        return array("services" => $user->getUserServices());
    }

    /**
     * @Route("/add/service", name="add-worker-service")
     * @Template("AppBundle:Worker:addupdateService.html.twig")
     */
    public function addServiceAction(Request $request)
    {
        $userservice = new UserService();
        $form = $this->createForm('userservice', $userservice)
            ->add("save","submit", array("label"=>"Add Service"));
        $form->handleRequest($request);
        if($form->isValid()){
            $userservice = $form->getData();
            $userservice->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($userservice);
            $em->flush();

            return $this->redirectToRoute("list-worker-services",array(), 301);
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param UserService $userservice
     * @return array
     * @Route("/edit/service/{id}", name="edit-worker-service")
     * @ParamConverter("userservice", class="AppBundle:UserService")
     * @Template("AppBundle:Worker:addupdateService.html.twig")
     */
    public function editServiceAction(Request $request, UserService $userservice)
    {
        $this->denyAccessUnlessGranted('update', $userservice, 'Unauthorized access!');
        $form = $this->createForm('userservice', $userservice)
            ->add("save","submit", array("label"=>"Save Service"));
        $form->handleRequest($request);
        if($form->isValid()){
            $userservice = $form->getData();
            $userservice->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($userservice);
            $em->flush();

            return $this->redirectToRoute("list-worker-services",array(), 301);
        }
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param UserService $userService
     * @Route("/delete/service/{id}", name="delete-worker-service")
     * @ParamConverter("userservice", class="AppBundle:UserService")
     */
    public function deleteServiceAction(UserService $userservice)
    {
        $this->denyAccessUnlessGranted('remove', $userservice, 'Unauthorized access!');
        $em = $this->getDoctrine()->getManager();
        $em->remove($userservice);
        $em->flush();

        return $this->redirectToRoute("list-worker-services",array(), 301);
    }

    /**
     * @return array
     * @Route("/worker/skill", name="worker-skill")
     * @Template
     */
    public function updateSkillsAction(Request $request){
        $user = $this->getUser();
        $arrUserSkills = $user->getUserSkills();
        $default_skills = "";
        foreach($arrUserSkills as $key => $objSkill){
            $default_skills .= $objSkill->getSkill() . ",";
        }

        $form = $this->createFormBuilder()
            ->add("skills","text",
                array(
                    'data' => $default_skills,
                    'constraints' => array(
                        new NotBlank()
                    )
                )
            )
            ->add('save','submit')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $skills = $form->get("skills")->getData();
            $workerProfileManager = $this->get("jsn.manager.worker.profile");
            $workerProfileManager->updateWorkerSkills($skills, $user);
        }

        return array('form'=> $form->createView());
    }

    /**
     * @param Request $request
     * @Route("/worker/rate", name="worker-rate")
     * @Template
     */
    public function updateRateAction(Request $request)
    {
        $user = $this->getUser();
        $userrate = $user->getUserRate();
        if(!$userrate){
            $userrate = new UserRate();
            $userrate->setUser($user);
        }
        $form = $this->createForm("userrate", $userrate)
            ->add('save', 'submit', array('label'=> 'Save'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $userRate = $form->getData();
            $workerProfileManager = $this->get("jsn.manager.worker.profile");
            $workerProfileManager->updateWorkerRate($userRate);
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Job $job
     * @Route("apply/job/{id}", name="apply-job")
     * @ParamConverter("job", class="AppBundle:Job")
     * @Template
     */
    public function applyForJobAction(Request $request, Job $job)
    {
        $errors = array();
        $jobApplication = new JobApplication();
        $jobApplication->setType("anynomous");
        $jobApplication->resumeselect = 'new';
        $arrChoice = array('new'=> 'Click Upload Resume');
        if($this->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
            $user = $this->getUser();
            $jobManager = $this->get("jsn.manager.job");
            $jobApplication = $jobManager->setJobApplicationDefaults($user);
            if ($jobApplication->getUserResume()) {
                $jobApplication->resumeselect = 'existing';
                $arrChoice['existing'] = $jobApplication->getUserResume()->getResumeFileName();
            }

        }
        $jobApplication->setJob($job);
        $form = $this->createForm('jobapplication', $jobApplication)
            ->add('uploadfile','button', array('label' => 'Upload Resume'))
            ->add('applyjob', 'submit', array('label'=> 'Submit Application'))
            ->add('resumeselect','choice',[
                'choices' => $arrChoice,
                'multiple' =>false,
                'expanded' => true
            ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $jobApplication = $form->getData();
            $jobApplication->setApplicationDate(time());
            if ($this->isGranted("IS_AUTHENTICATED_REMEMBERED")) {
                if ($jobApplication->resumeselect == "new") {
                    $userResume = new UserResume();
                    $userResume->setUploadedFile($jobApplication->getUploadedFile());
                    $userResume->setUser($user);
                    $jobApplication->setUserResume($userResume);
                }
                $jobApplication->setUploadedFile(null);
                $jobApplication->setResume(null);
            } else {
                $validator = $this->get('validator');
                $errors = $validator->validate($jobApplication, null, array("anonymousapplication"));
            }
            if (count($errors) == 0) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($jobApplication);
                $em->flush();
            }
        }

        return array(
            'form' => $form->createView(),
            'job' => $job,
            'errors' => $errors
        );
    }

    /**
     * @return array
     * @Route("/list/applications", name="list-applications")
     * @Template
     */
    public function listApplicationsAction()
    {
        $user = $this->getUser();
        $applications = $this->get("jsn.manager.worker.profile")->getWorkerApplications($user);

        return array("applications" => $applications);
    }

    /**
     * @param JobApplication $application
     * @return array
     * @Route("/job/application/{id}", name="view-application-detail")
     * @ParamConverter("application", class="AppBundle:JobApplication")
     * @Template
     */
    public function viewApplicationDetailAction(JobApplication $application)
    {
        $this->denyAccessUnlessGranted('view', $application, 'Unauthorized access!');
        return (array("application" => $application));
    }


    /**
     * @Route("/search/worker", name="search-worker")
     * @Template
     */
    public function searchWorkersAction(Request $request)
    {
        $workers = array();
        $data = array();
        $form = $this->createFormBuilder()
            ->add('searchKey', 'text', array('required' => false))
            ->add('jobCategory', 'entity', array(
                'class' => 'AppBundle:JobCategory',
                'choice_label' => 'title',
                'required' => false,
                'placeholder' => 'Any',
            ))
            ->add('location', 'text', array('required' => false))
            ->add('rateType', 'entity', array(
                'class' => 'AppBundle:RateType',
                'choice_label' => 'rate_type',
                'required' => false,
                'placeholder' => 'Any'
            ))
            ->add('rate', 'number', array('required' => false))
            ->add('btnsearch', 'submit', array('label' => 'Search'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
        }
        $workers = $this->get("jsn.manager.user")->searchWorkers($data);
        return array(
            'form' => $form->createView(),
            'workers' => $workers,
        );
    }
}