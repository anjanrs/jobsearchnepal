<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageVoter extends AbstractVoter
{
    const VIEW = 'view';
    const UPDATE = 'update';

    protected function getSupportedAttributes()
    {
        return array(self::VIEW, self::UPDATE);
    }

    protected function getSupportedClasses()
    {
        return array('AppBundle\Entity\Message');
    }

    protected function isGranted($attribute, $message, $user = null)
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
                if ($user->getId() === $message->getToUser()->getId() || $user->getId() === $message->getFromUser()->getId()) {
                    return true;
                }
                break;
            case self::UPDATE:
                    return false;
                break;
        }

        return false;
    }
}