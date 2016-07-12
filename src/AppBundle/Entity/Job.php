<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity
 * @ORM\Table(name="job")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\JobRepository")
 * @JMS\ExclusionPolicy("all")
 */
class Job
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMS\Expose()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Job Title cannot be empty", groups={"postjob"})
     * @JMS\Expose()
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    protected $terse;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Job Description cannot be empty", groups={"postjob"})
     */
    protected $description;

    /**
     * @ORM\Column(name="post_date", type="integer", length=11)
     * @Assert\NotBlank()
     * @JMS\Expose()
     */
    protected $postDate;

    /**
     * @ORM\Column(type="integer", length=11, name="valid_till")
     * @Assert\NotBlank(message="Job Validity date cannot be empty", groups={"postjob"})
     * @JMS\Expose()
     */
    protected $validTill;

//    /**
//     * @ORM\Column(type="integer", length=11, name="employer_id")
//     * @Assert\NotBlank()
//     * @JMS\Expose()
//     */
//    protected $employerId;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="employer_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Employer cannot be blank", groups={"postjob"})
     */
    private $employer;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $rate;

    /**
     * @ORM\ManyToOne(targetEntity="RateType")
     * @ORM\JoinColumn(name="rate_type_id", referencedColumnName="id")
     */
    protected $rateType;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Location cannot be empty", groups={"postjob"})
     */
    protected $location;

    /**
     * @ORM\ManyToOne(targetEntity="JobCategory")
     * @ORM\JoinColumn(name="job_category_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Category cannot be empty", groups={"postjob"})
     */
    protected $jobCategory;

    /**
     * @ORM\ManyToOne(targetEntity="JobType")
     * @ORM\JoinColumn(name="job_type_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Job Type cannot be empty", groups={"postjob"})
     */
    private $jobType;
    
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
     * @return Job
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
     * @return Job
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
     * Set postDate
     *
     * @param integer $postDate
     *
     * @return Job
     */
    public function setPostDate($postDate)
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get postDate
     *
     * @return integer
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * Set validTill
     *
     * @param integer $validTill
     *
     * @return Job
     */
    public function setValidTill($validTill)
    {
        $this->validTill = $validTill;

        return $this;
    }

    /**
     * Get validTill
     *
     * @return integer
     */
    public function getValidTill()
    {
        return $this->validTill;
    }

//    /**
//     * Set employerId
//     *
//     * @param integer $employerId
//     *
//     * @return Job
//     */
//    public function setEmployerId($employerId)
//    {
//        $this->employerId = $employerId;
//
//        return $this;
//    }
//
//    /**
//     * Get employerId
//     *
//     * @return integer
//     */
//    public function getEmployerId()
//    {
//        return $this->employerId;
//    }

    /**
     * Set rate
     *
     * @param string $rate
     *
     * @return Job
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
     * Set location
     *
     * @param string $location
     *
     * @return Job
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set terse
     *
     * @param string $terse
     *
     * @return Job
     */
    public function setTerse($terse)
    {
        $this->terse = $terse;

        return $this;
    }

    /**
     * Get terse
     *
     * @return string
     */
    public function getTerse()
    {
        return $this->terse;
    }

    /**
     * Set rateType
     *
     * @param \AppBundle\Entity\RateType $rateType
     *
     * @return Job
     */
    public function setRateType(\AppBundle\Entity\RateType $rateType = null)
    {
        $this->rateType = $rateType;

        return $this;
    }

    /**
     * Get rateType
     *
     * @return \AppBundle\Entity\RateType
     */
    public function getRateType()
    {
        return $this->rateType;
    }

    /**
     * Set jobCategory
     *
     * @param \AppBundle\Entity\JobCategory $jobCategory
     *
     * @return Job
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
     * Set jobType
     *
     * @param \AppBundle\Entity\JobType $jobType
     *
     * @return Job
     */
    public function setJobType(\AppBundle\Entity\JobType $jobType = null)
    {
        $this->jobType = $jobType;

        return $this;
    }

    /**
     * Get jobType
     *
     * @return \AppBundle\Entity\JobType
     */
    public function getJobType()
    {
        return $this->jobType;
    }

    /**
     * Set employer
     *
     * @param \AppBundle\Entity\User $employer
     *
     * @return Job
     */
    public function setEmployer(\AppBundle\Entity\User $employer = null)
    {
        $this->employer = $employer;

        return $this;
    }

    /**
     * Get employer
     *
     * @return \AppBundle\Entity\User
     */
    public function getEmployer()
    {
        return $this->employer;
    }
}
