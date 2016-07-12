<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Entity\User;
use AppBundle\Entity\RateType;

/**
 * UserRate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRateRepository")
 */
class UserRate
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
     * @ORM\Column(name="rate", type="decimal")
     * @Assert\NotBlank(message="Rate cannot be blank", groups={"userrate"})
     */
    private $rate;

    /**
     * @var
     * @ORM\ManyToOne(targetEntity="RateType")
     * @ORM\JoinColumn(name="rate_type_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Rate Type be blank", groups={"userrate"})
     */
    private $rateType;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="userRate")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\NotBlank(message="User cannot be blank", groups={"userrate"})
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
     * Set rate
     *
     * @param string $rate
     *
     * @return UserRate
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
     * Set rateType
     *
     * @param \AppBundle\Entity\RateType $rateType
     *
     * @return UserRate
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
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserRate
     */
    public function setUser(\AppBundle\Entity\User $user = null)
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
