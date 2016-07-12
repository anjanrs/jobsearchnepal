<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Messages
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MessageRepository")
 */
class Message
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Assert\NotBlank(message="Message cannot be blank", groups={"message"})
     */
    private $message;

    /**
     * @var integer
     *
     * @ORM\Column(name="message_datetime", type="integer")
     * @Assert\NotBlank(message="MessageDateTime cannot be blank", groups={"message"})
     */
    private $messageDatetime;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="from_user_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Message from user cannot be blank", groups={"message"})
     */
    private $fromUser;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="to_user_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Message to user cannot be blank", groups={"message"})
     */
    private $toUser;

    /**
     * @ORM\ManyToOne(targetEntity="JobApplication")
     * @ORM\JoinColumn(name="job_application_id", referencedColumnName="id")
     */
    private $jobApplication;


    /**
     * @ORM\ManyToOne(targetEntity="Message")
     * @ORM\JoinColumn(name="reply_to_message_id", referencedColumnName="id")
     */
    private $replyToMessage;

    /**
     * @ORM\Column(name="read_before", type="boolean")
     * @Assert\NotNull(message="Message read indication cannot be blank", groups={"message"})
     */
    private $readBefore = false;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Messages
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set messageDatetime
     *
     * @param integer $messageDatetime
     *
     * @return Messages
     */
    public function setMessageDatetime($messageDatetime)
    {
        $this->messageDatetime = $messageDatetime;

        return $this;
    }

    /**
     * Get messageDatetime
     *
     * @return integer
     */
    public function getMessageDatetime()
    {
        return $this->messageDatetime;
    }

    /**
     * Set fromUser
     *
     * @param \AppBundle\Entity\User $fromUser
     *
     * @return Messages
     */
    public function setFromUser(\AppBundle\Entity\User $fromUser = null)
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    /**
     * Get fromUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getFromUser()
    {
        return $this->fromUser;
    }

    /**
     * Set toUser
     *
     * @param \AppBundle\Entity\User $toUser
     *
     * @return Messages
     */
    public function setToUser(\AppBundle\Entity\User $toUser = null)
    {
        $this->toUser = $toUser;

        return $this;
    }

    /**
     * Get toUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getToUser()
    {
        return $this->toUser;
    }

    /**
     * Set jobApplication
     *
     * @param \AppBundle\Entity\JobApplication $jobApplication
     *
     * @return Messages
     */
    public function setJobApplication(\AppBundle\Entity\JobApplication $jobApplication = null)
    {
        $this->jobApplication = $jobApplication;

        return $this;
    }

    /**
     * Get jobApplication
     *
     * @return \AppBundle\Entity\JobApplication
     */
    public function getJobApplication()
    {
        return $this->jobApplication;
    }

    /**
     * Set replyToMessage
     *
     * @param \AppBundle\Entity\Message $replyToMessage
     *
     * @return Message
     */
    public function setReplyToMessage(\AppBundle\Entity\Message $replyToMessage = null)
    {
        $this->replyToMessage = $replyToMessage;

        return $this;
    }

    /**
     * Get replyToMessage
     *
     * @return \AppBundle\Entity\Message
     */
    public function getReplyToMessage()
    {
        return $this->replyToMessage;
    }

    /**
     * Set read
     *
     * @param boolean $read
     *
     * @return Message
     */
    public function setReadBefore($readBefore)
    {
        $this->readBefore = $readBefore;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function getReadBefore()
    {
        return $this->readBefore;
    }
}
