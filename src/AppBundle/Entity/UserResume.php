<?php

namespace AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Model\AbstractFileUploadModel;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * UserResume
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserResumeRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserResume extends AbstractFileUploadModel
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
     * @ORM\Column(name="resume_file_name", type="string", length=255)
     * @Assert\NotBlank(message="Please upload your resume", groups={"resume"})
     */
    private $resumeFileName;

    /**
     * @var integer
     *
     * @ORM\Column(name="preferred", type="integer")
     * @Assert\NotBlank(message="Preferred cannot be blank", groups={"resume"})
     */
    private $preferred = 0;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userResumes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @Assert\File(
     *  maxSize = "8M"
     * )
     */
    protected $uploadedFile;
    protected $uploadDir = 'data/uploads/worker/resume';

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
     * Set resumeFileName
     *
     * @param string $resumeFileName
     *
     * @return UserResume
     */
    public function setResumeFileName($resumeFileName)
    {
        $this->resumeFileName = $resumeFileName;

        return $this;
    }

    /**
     * Get resumeFileName
     *
     * @return string
     */
    public function getResumeFileName()
    {
        return $this->resumeFileName;
    }

    /**
     * Set preferred
     *
     * @param integer $preferred
     *
     * @return UserResume
     */
    public function setPreferred($preferred)
    {
        $this->preferred = $preferred;

        return $this;
    }

    /**
     * Get preferred
     *
     * @return integer
     */
    public function getPreferred()
    {
        return $this->preferred;
    }

    protected function setStoredFileName($filename)
    {
        return $this->setResumeFileName($filename);
    }

    protected function getStoredFileName()
    {
        return $this->getResumeFileName();
    }

    protected function generateFileName()
    {
        $original_name = $this->getUploadedFile()->getClientOriginalName();
        $file_name = explode(".",$original_name);

        return time() . '-' . $file_name[0];
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserResume
     */
    public function setUser($user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
