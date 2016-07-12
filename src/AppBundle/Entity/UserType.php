<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserType
 *
 * @ORM\Table(name="user_type")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserTypeRepository")
 */
class UserType
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
     * @ORM\Column(name="user_type", type="string", length=25)
     */
    private $userType;

    /**
     * @var string
     *
     * @ORM\Column(name="user_type_desc", type="string", length=100)
     */
    private $userTypeDesc;


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
     * Set userType
     *
     * @param string $userType
     *
     * @return UserType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return string
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * Set userTypeDesc
     *
     * @param string $userTypeDesc
     *
     * @return UserType
     */
    public function setUserTypeDesc($userTypeDesc)
    {
        $this->userTypeDesc = $userTypeDesc;

        return $this;
    }

    /**
     * Get userTypeDesc
     *
     * @return string
     */
    public function getUserTypeDesc()
    {
        return $this->userTypeDesc;
    }
}
