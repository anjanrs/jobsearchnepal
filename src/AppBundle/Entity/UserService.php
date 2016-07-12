<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\RateType;
use AppBundle\Entity\JobCategory;
use AppBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserService
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserServiceRepository")
 */
class UserService
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank(message="Title cannot be empty.", groups={"userservice"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="Description cannot be empty.", groups={"userservice"})
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="decimal")
     */
    private $rate;

    /**
     * @var string
     *
     * @ORM\Column(name="minimum_budget", type="decimal")
     */
    private $minimumBudget;


    /**
     * @ORM\ManyToOne(targetEntity="RateType")
     * @ORM\JoinColumn(name="rate_type_id", referencedColumnName="id")
     */
    private $rateType;

    /**
     * @ORM\ManyToOne(targetEntity="JobCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Job category cannot be empty.", groups={"userservice"})
     */
    private $jobCategory;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="User", inversedBy="userServices")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
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
     * Set title
     *
     * @param string $title
     *
     * @return UserService
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return UserService
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return UserService
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set minimumBudget
     *
     * @param string $minimumBudget
     *
     * @return UserService
     */
    public function setMinimumBudget($minimumBudget)
    {
        $this->minimumBudget = $minimumBudget;

        return $this;
    }

    /**
     * Get minimumBudget
     *
     * @return string
     */
    public function getMinimumBudget()
    {
        return $this->minimumBudget;
    }

    public function setRateType(RateType $rateType = null)
    {
        $this->rateType = $rateType;

        return $this;
    }

    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\JobCategory $jobCategory
     *
     * @return UserService
     */
    public function setCategory(JobCategory $category = null)
    {
        $this->jobCategory = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\JobCategory
     */
    public function getCategory()
    {
        return $this->jobCategory;
    }

    /**
     * Set jobCategory
     *
     * @param \AppBundle\Entity\JobCategory $jobCategory
     *
     * @return UserService
     */
    public function setJobCategory(\AppBundle\Entity\JobCategory $jobCategory = null)
    {
        $this->jobCategory = $jobCategory;

        return $this;
    }

    /**
     * Get jobCategory
     *
     * @return \AppBundle\Entity\JobCategory
     */
    public function getJobCategory()
    {
        return $this->jobCategory;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserService
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
}
