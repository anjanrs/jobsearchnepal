<?php

namespace AppBundle\Service;


use FOS\RestBundle\Request\ParamFetcher;
use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\Job;
use AppBundle\Entity\UserType;
use AppBundle\Entity\User;
use AppBundle\Entity\UserResume;
use AppBundle\Entity\JobApplication;

class JobManager
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function postJob(Job $job)
    {
        $job->setTerse(substr($job->getDescription(),0,20) . "...");
        $job->setPostDate(time());
        $em = $this->doctrine->getManager();
        $em->persist($job);
        $em->flush();
    }

    public function getAllJobsPosted(User $user)
    {
        $pagesize = 10;
        $page = 0;
        $orderby = 'postDate';
        $order = 'DESC';

        $repository = $this->doctrine->getRepository("AppBundle:Job");
        $jobs_posted = $repository->findBy(
            array("employer" => $user),
            array("$orderby" => $order),
            $pagesize,
            ($pagesize * $page)

        );

        return $jobs_posted;
    }

    public function getJobsPosted(User $user, ParamFetcher $paramFetcher)
    {
        $pagesize = $paramFetcher->get("jtPageSize");
        $page = $paramFetcher->get("jtStartIndex");
        $arrorderby = $paramFetcher->get("jtSorting");
        $arrorderby = explode(" ",$arrorderby);
        $orderby = $arrorderby[0];
        $order = $arrorderby[1];

        $repository = $this->doctrine->getRepository("AppBundle:Job");
        $jobs_posted = $repository->findBy(
            array("employer" => $user),
            array("$orderby" => $order),
            $pagesize,
            ($pagesize * $page)

        );

        return $jobs_posted;
    }

    public function setJobApplicationDefaults(User $user)
    {
        $jobApplication = new JobApplication();
        $jobApplication->setName($user->getFullname());
        $jobApplication->setEmail($user->getEmail());
        $jobApplication->setPhoneNo($user->getUserProfile()->getPhoneNumber());
        $jobApplication->setType("registered");
        $jobApplication->setUser($user);
//        $em = $this->doctrine->getEntityManager();

        $resumes = $user->getUserResumes();
        foreach($resumes as $key=>$resume) {
            if ($resume->getPreferred()) {
//                $proxy =$em->getReference("AppBundle:UserResume", $resume->getId());
                $jobApplication->setUserResume($resume);
                $jobApplication->resumeid = $resume->getId();
                break;
            }
        }
        return $jobApplication;
    }

    public function getJobApplications(Job $job)
    {
        $applications = $this->doctrine->getRepository("AppBundle:JobApplication")
            ->findBy(
                array("job" => $job->getId()),
                array("applicationDate" => "DESC")
            );
        return $applications;
    }

}