<?php

namespace AppBundle\Entity;

use AppBundle\Model\AbstractFileUploadModel;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserProfile
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserProfileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class UserProfile extends AbstractFileUploadModel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="profile_id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=100)
     * @Assert\NotBlank(message="Company Name cannot be blank", groups={"personalinfo"})
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone_number", type="string", length=20)
     * @Assert\NotBlank(message="Phone Number cannot be blank", groups={"personalinfo"})
     */
    private $phoneNumber;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="userProfile")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;


    /**
     * @ORM\Column(name="screen_name", type="string", length=20)
     * @Assert\NotBlank(message="Screen Name cannot be blank", groups={"screenidentity"})
     */
    private $screenName;

    /**
     * @ORM\Column(name="screen_image", type="string", length=500)
     * @Assert\NotBlank(message="Please upload your picture for screen image", groups={"screenidentity"})
     */
    private $screenImage;

    /**
     * @Assert\Image(
     *  minWidth = 200,
     *  maxWidth = 400,
     *  minHeight = 200,
     *  maxHeight = 400,
     *  maxSize = "8M"
     * )
     */
    protected $uploadedFile;
    protected $uploadDir = 'web/images/uploads/emp/screenidentity';

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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return UserProfile
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set phoneNumber
     *
     * @param string $phoneNumber
     *
     * @return UserProfile
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserProfile
     */
    public function setUser(User $user = null)
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

    /**
     * Set screenName
     *
     * @param string $screenName
     *
     * @return UserProfile
     */
    public function setScreenName($screenName)
    {
        $this->screenName = $screenName;

        return $this;
    }

    /**
     * Get screenName
     *
     * @return string
     */
    public function getScreenName()
    {
        return $this->screenName;
    }

    /**
     * Set screenImage
     *
     * @param string $screenImage
     *
     * @return UserProfile
     */
    public function setScreenImage($screenImage)
    {
        $this->screenImage = $screenImage;

        return $this;
    }

    /**
     * Get screenImage
     *
     * @return string
     */
    public function getScreenImage()
    {
        return $this->screenImage;
    }


    protected function setStoredFileName($filename)
    {
        return $this->setScreenImage($filename);
    }

    protected function getStoredFileName()
    {
        return $this->getScreenImage();
    }

    protected function generateFileName()
    {
        return 'emp-' . time();
    }
}
