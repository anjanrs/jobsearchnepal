<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class JobVoter extends AbstractVoter
{
    const VIEW = 'view';
    const UPDATE = 'update';
    const REMOVE = 'remove';

    protected function getSupportedAttributes()
    {
        return array(self::VIEW, self::UPDATE, self::REMOVE);
    }

    protected function getSupportedClasses()
    {
        return array('AppBundle\Entity\Job');
    }

    protected function isGranted($attribute, $job, $user = null)
    {
        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof UserInterface) {
            return false;
        }

        // double-check that the User object is the expected entity (this
        // only happens when you did not configure the security system properly)
        if (!$user instanceof User) {
            throw new \LogicException('The user is somehow not our User class!');
        }

        switch($attribute) {
            case self::VIEW:
            case self::UPDATE:
            case self::REMOVE:
                if ($user->getId() === $job->getEmployer()->getId()) {
                    return true;
                }
                break;
        }
        return false;
    }
}