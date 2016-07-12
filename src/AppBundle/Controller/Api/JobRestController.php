<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Request\ParamFetcher;

class JobRestController extends FOSRestController
{
    /**
     * @return array
     * @FOSRest\Post("/api/job/posts")
     * @FOSRest\QueryParam(name="jtStartIndex", requirements= "\d+", default="0", allowBlank=false, description="Start Index")
     * @FOSRest\QueryParam(name="jtPageSize", requirements= "\d+", default="100", allowBlank=false, description="Page Size")
     * @FOSRest\QueryParam(name="jtSorting", allowBlank=false, description="Sorting")
     * @param ParamFetcher $paramFetcher
     */
    public function getJobPostsAction(ParamFetcher $paramFetcher)
    {
        $user = $this->getUser();
        $job_manager = $this->get("jsn.manager.job");
        $jobs_posted = $job_manager->getJobsPosted($user, $paramFetcher);
        $result = array("Result"=> "OK", "Records"=> $jobs_posted);
        return $result;
    }
}