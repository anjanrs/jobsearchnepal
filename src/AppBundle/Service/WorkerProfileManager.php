<?php

namespace AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\User;
use AppBundle\Entity\Skill;
use AppBundle\Entity\UserRate;

class WorkerProfileManager
{
    private $doctrine;

    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function updateWorkerSkills($skills, User $user){
        $arrSubmittedSkills = explode(",",$skills);
        $arrUserSkills = $user->getUserSkills();

        $default_skills = "";
        foreach($arrUserSkills as $key => $objSkill){
            if(in_array($objSkill->getSkill(), $arrSubmittedSkills)){
                unset($arrSubmittedSkills[$key]);
            } else {
                $user->removeUserSkill($objSkill);
            }
        }
        foreach($arrSubmittedSkills as $userskill){
            $objSkill = $this->doctrine->getRepository("AppBundle:Skill")
                ->findOneBy(array("skill"=>$userskill));
            if (!is_object($objSkill)) {
                $objSkill = new Skill();
                $objSkill->setSkill($userskill);
            }
            $user->addUserSkill($objSkill);
        }
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();
    }

    public function updateWorkerRate(UserRate $userRate)
    {
        $em = $this->doctrine->getManager();
        $em->persist($userRate);
        $em->flush();
    }

    public function getWorkerApplications(User $user)
    {
        $applications = $this->doctrine->getRepository("AppBundle:JobApplication")
            ->findBy(
                array("user" => $user->getId()),
                array("applicationDate" => "DESC")
            );

        return $applications;
    }
}
