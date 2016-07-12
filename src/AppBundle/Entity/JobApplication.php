<?php

namespace AppBundle\Entity;

use AppBundle\Model\AbstractFileUploadModel;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;
use AppBundle\Entity\Job;
use AppBundle\Entity\UserResume;


/**
 * JobApplication
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\JobApplicationRepository")
 * @ORM\HasLifecycleCallbacks
 */
class JobApplication extends AbstractFileUploadModel
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Name cannot be blank", groups={"jobapplication"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\NotBlank(message="Email cannot be blank", groups={"jobapplication"})
     * @Assert\Email(message="Invalid Email address", groups={"jobapplication"})
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_no", type="string", length=255)
     */
    private $phoneNo;

    /**
     * @var string
     *
     * @ORM\Column(name="cover_letter", type="text")
     */
    private $coverLetter;

    /**
     * @var string
     *
     * @ORM\Column(name="resume", type="string", length=800)
     * @Assert\NotBlank(message="No resume uploaded", groups={"anonymousapplication"})
     */
    private $resume;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     * @Assert\NotBlank(message="Type cannot be blank", groups={"jobapplication"})
     * @Assert\Choice(choices={"registered", "anynomous"}, message="Invalid type")
     */
    private $type;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Job")
     * @ORM\JoinColumn(name="job_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Job cannot be blank", groups={"jobapplication"})
     */
    private $job;

    /**
     * @ORM\ManyToOne(targetEntity="UserResume", cascade={"persist"})
     * @ORM\JoinColumn(name="resume_id", referencedColumnName="id")
    */
    private $userResume;

    /**
     * @Assert\File(
     *  maxSize = "8M"
     * )
     * @Assert\NotBlank(message="Please upload resume", groups={"anonymousapplication"})
     */
    protected $uploadedFile;
    protected $uploadDir = 'data/uploads/application/resume';

    /**
     * @ORM\Column(name="application_date", type="integer", length=11)
     * @Assert\NotBlank()
     */
    private $applicationDate;

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
     * Set name
     *
     * @param string $name
     *
     * @return JobApplication
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return JobApplication
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phoneNo
     *
     * @param string $phoneNo
     *
     * @return JobApplication
     */
    public function setPhoneNo($phoneNo)
    {
        $this->phoneNo = $phoneNo;

        return $this;
    }

    /**
     * Get phoneNo
     *
     * @return string
     */
    public function getPhoneNo()
    {
        return $this->phoneNo;
    }

    /**
     * Set coverLetter
     *
     * @param string $coverLetter
     *
     * @return JobApplication
     */
    public function setCoverLetter($coverLetter)
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }

    /**
     * Get coverLetter
     *
     * @return string
     */
    public function getCoverLetter()
    {
        return $this->coverLetter;
    }

    /**
     * Set resume
     *
     * @param string $resume
     *
     * @return JobApplication
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return JobApplication
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     *
     * @return JobApplication
     */
    public function setUser(User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set job
     *
     * @param \stdClass $job
     *
     * @return JobApplication
     */
    public function setJob(Job $job)
    {
        $this->job = $job;

        return $this;
    }

    /**
     * Get job
     *
     * @return \stdClass
     */
    public function getJob()
    {
        return $this->job;
    }

    protected function setStoredFileName($filename)
    {
        return $this->setResume($filename);
    }

    protected function getStoredFileName()
    {
        return $this->getResume();
    }

    protected function generateFileName()
    {
        $original_name = $this->getUploadedFile()->getClientOriginalName();
        $file_name = explode(".",$original_name);

        return time() . '-' . $file_name[0];
    }

    /**
     * Set userResume
     *
     * @param \AppBundle\Entity\UserResume $userResume
     *
     * @return JobApplication
     */
    public function setUserResume(UserResume $userResume = null)
    {
        $this->userResume = $userResume;

        return $this;
    }

    /**
     * Get userResume
     *
     * @return \AppBundle\Entity\UserResume
     */
    public function getUserResume()
    {
        return $this->userResume;
    }

    /**
     * Set applicationDate
     *
     * @param integer $applicationDate
     *
     * @return JobApplication
     */
    public function setApplicationDate($applicationDate)
    {
        $this->applicationDate = $applicationDate;

        return $this;
    }

    /**
     * Get applicationDate
     *
     * @return integer
     */
    public function getApplicationDate()
    {
        return $this->applicationDate;
    }
}
