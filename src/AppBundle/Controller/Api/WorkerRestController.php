<?php
namespace AppBundle\Controller\Api;

use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOSRest;
use FOS\RestBundle\Controller\FOSRestController;
use AppBundle\Entity\Skill;

class WorkerRestController extends FOSRestController
{
    /**
     * @FOSRest\Get("/api/worker/skills/{searchKey}")
     * @param ParamFetcher $paramFetcher
     */
    function getWorkerSkills($searchKey)
    {
        return $this->getDoctrine()->getRepository("AppBundle:Skill")->searchSkills($searchKey);
    }
}