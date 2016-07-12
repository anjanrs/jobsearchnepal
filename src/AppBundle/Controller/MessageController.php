<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\User;
use AppBundle\Entity\Message;

class MessageController extends Controller
{
	    /**
     * @Route("emp/job/application/{id}", name="emp-job-application-detail")
     * @ParamConverter("application", class="AppBundle:JobApplication")
     * @Template
     */

	/**
	 * @Route("message/inbox", name="msg-inbox")
 	 * @Template
	 */
	public function inboxMsgAction()
	{
		$user = $this->getUser();
		$messages = $this->get("jsn.manager.message")->getInboxMsgs($user);
		return array('messages' => $messages);
	}

	/**
	 * @Route("message/sent", name="msg-sent")
 	 * @Template
	 */
	public function sentMsgAction()
	{
		$user = $this->getUser();
		$messages = $this->get("jsn.manager.message")->getSentMsgs($user);
		return array('messages' => $messages);
	}

	/**
	 * @Route("message/detail/{id}", name="msg-detail")
	 * @ParamConverter("msg", class="AppBundle:Message")
	 * @Template
	 */
	public function detailMsgAction(Request $request, Message $msg)
	{
        $this->denyAccessUnlessGranted('view', $msg, 'Unauthorized access!');

		if (!$msg->getReadBefore()) {
			$this->get("jsn.manager.message")->updaetMsgAsRead($msg);	
		}
		$user = $this->getUser();
       	$message = new Message();
		$message->setFromUser($user);
		if ($msg->getFromUser()->getId() == $user->getId()) {			
			$message->setToUser($msg->getToUser());	
		}
		else {
			$message->setToUser($msg->getFromUser());		
		}
		$message->setMessageDateTime(time());

		$form = $this->createForm('message', $message)
			->add('send_msg','submit', array('label'=> 'Send Message'));
		$form->handleRequest($request);
		if ($form->isValid()) {
			$message = $form->getData();
			$str_msg = $message->getMessage() . "<br><br><br>" .
				"==================================================" . "<br>" . 
				"From " . $msg->getFromUser()->getFullName() . "<br>" .
				"==================================================" . "<br>" .
				$msg->getMessage();
			$message->setMessage($str_msg);
			$this->get("jsn.manager.message")->createMessage($message);
		}
		
		return array(
			'msg' => $msg,
			'form' => $form->createView()
		);
	}
}