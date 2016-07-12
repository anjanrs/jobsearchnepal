<?php

namespace AppBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\User;
use AppBundle\Entity\Message;

class MessageManager
{
	private $doctrine;

	public function __construct(Registry $doctrine)
	{
		$this->doctrine = $doctrine;
	}

	public function getInboxMsgs(User $user)
	{
		$messages =  $this->doctrine
			->getManager()
			->getRepository("AppBundle:Message")
			->findBy(
				array('toUser' => $user->getId()),
				array('messageDatetime' => 'DESC')
			);
		return $messages;

	}

	public function getSentMsgs(User $user)
	{
		$messages =  $this->doctrine
			->getManager()
			->getRepository("AppBundle:Message")
			->findBy(
				array('fromUser' => $user->getId()),
				array('messageDatetime' => 'DESC')
			);
		return $messages;
	}

	public function createMessage(Message $message) 
	{
		$em = $this->doctrine->getEntityManager();
        $em->persist($message);
        $em->flush();
	}

	public function updaetMsgAsRead(Message $message)
	{
		$message->setReadBefore(true);
		$em = $this->doctrine->getEntityManager();
        $em->persist($message);
        $em->flush();	
	}
}