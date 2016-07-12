<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use AppBundle\Entity\UserType;
use AppBundle\Entity\Role;
use Doctrine\Common\Collections\ArrayCollection;
use AppBundle\Entity\UserProfile;
use AppBundle\Entity\UserAddress;
use AppBundle\Entity\UserResume;
use AppBundle\Entity\UserService;
use AppBundle\Entity\Skill;

/**
 * @ORM\Table(name="app_users")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\UserRepository")
 * @UniqueEntity(fields="email", message="Sorry, this email address is already in use.", groups={"registration"})
 * @UniqueEntity(fields="username", message="Sorry, this username is already taken.", groups={"registration"})
 */
class User implements AdvancedUserInterface, \Serializable {
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\NotBlank()
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank(message="Password cannot be blank", groups={"registration"})
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\Email(message="Email address is invalid", groups={"registration", "personalinfo"})
     * @Assert\NotBlank(message="Email cannot be blank", groups={"registration", "personalinfo"})
     */
    private $email;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     * @Assert\NotBlank()
     */
    private $isActive;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Full name cannot be blank", groups={"registration"})
     */
    private $fullname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="First Name cannot be blank", groups={"personalinfo"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="First Name cannot be blank", groups={"personalinfo"})
     */
    private $lastname;

    /**
     * @ORM\ManyToOne(targetEntity="UserType")
     * @ORM\JoinColumn(name="user_type_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $userType;

    /**
     * @ORM\ManyToMany(targetEntity="Role")
     * @ORM\JoinTable(name="user_role",
     *   joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $userRoles;

    /**
     * @ORM\OneToOne(targetEntity="UserProfile", mappedBy="user", cascade={"persist"})
     */
    private $userProfile;

    /**
     * @ORM\OneToOne(targetEntity="UserAddress", mappedBy="user", cascade={"persist"})
     */
    private $userAddress;

    /**
     * @ORM\OneToMany(targetEntity="UserService", mappedBy="user", cascade={"persist"})
     */
     private $userServices;

    /**
     * @ORM\OneToMany(targetEntity="UserResume", mappedBy="user", cascade={"persist"})
     * @ORM\OrderBy({"preferred" = "DESC"})
     */
    private $userResumes;

    /**
     * @var
     * @ORM\ManyToMany(targetEntity="Skill", cascade={"persist"})
     * @ORM\JoinTable(name="user_skill",
     *  joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *  inverseJoinColumns={@ORM\JoinColumn(name="skill_id", referencedColumnName="id")}
     * )
     */
    private $userSkills;


    /**
     * @var
     * @ORM\OneToOne(targetEntity="UserRate", mappedBy="user", cascade={"persist"})
     */
    private $userRate;

    public function __construct() {
        #$this->isActive = true;
        // may not be needed, see section on salt below
        // $this->salt = md5(uniqid(null, true));
        $this->userRoles = new ArrayCollection();
        $this->userResumes = new ArrayCollection();
        $this->userServices = new ArrayCollection();
        $this->userSkills = new ArrayCollection();
    }

    public function getUsername() {
        return $this->username;
    }

    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return NULL;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRoles() {
        $roles = array();
        foreach ($this->userRoles as $userRole) {
            $roles[] = $userRole->getRole();
        }
        return $roles;
    }

    public function eraseCredentials() {
    }

    /** @see \Serializable::serialize() */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    public function isAccountNonExpired() {
        return TRUE;
    }

    public function isAccountNonLocked() {
        return TRUE;
    }

    public function isCredentialsNonExpired() {
        return TRUE;
    }

    public function isEnabled() {
        return $this->isActive;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     *
     * @return User
     */
    public function setFullname($fullname) {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string
     */
    public function getFullname() {
        return $this->fullname;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * Set userType
     *
     * @param \AppBundle\Entity\UserType $userType
     *
     * @return User
     */
    public function setUserType(UserType $userType = null)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return \AppBundle\Entity\UserType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /* Add User Role
    *
    * @param Role $userrole
    */
    public function addUserRole(Role $userrole)
    {
        if (!$this->userRoles->contains($userrole)) {
            $this->userRoles->add($userrole);
        }
    }

    public function setUserRoles($userroles)
    {
        if ($userroles instanceof ArrayCollection || is_array($userroles)) {
            foreach ($userroles as $userrole) {
                $this->addUserRole($userrole);
            }
        } elseif ($userroles instanceof Role) {
            $this->addUserRole($userroles);
        } else {
            throw new Exception($userroles ." must be an instance of Role or ArrayCollection");
        }
    }

    /**
     * Get ArrayCollection
     *
     * @return ArrayCollection $userrole
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }


    /**
     * Remove userRole
     *
     * @param \AppBundle\Entity\Role $userRole
     */
    public function removeUserRole(\AppBundle\Entity\Role $userRole)
    {
        $this->userRoles->removeElement($userRole);
    }

    /**
     * Set userprofile
     *
     * @param \AppBundle\Entity\UserProfile $userprofile
     *
     * @return User
     */
    public function setUserProfile(UserProfile $userProfile = null)
    {
        $userProfile->setUser($this);
        $this->userProfile = $userProfile;

        return $this;
    }

    /**
     * Get userprofile
     *
     * @return \AppBundle\Entity\UserProfile
     */
    public function getUserProfile()
    {
        return $this->userProfile;
    }

    /**
     * Set userAddress
     *
     * @param \AppBundle\Entity\UserAddress $userAddress
     *
     * @return User
     */
    public function setUserAddress(UserAddress $userAddress = null)
    {
        $userAddress->setUser($this);
        $this->userAddress = $userAddress;

        return $this;
    }

    /**
     * Get userAddress
     *
     * @return \AppBundle\Entity\UserAddress
     */
    public function getUserAddress()
    {
        return $this->userAddress;
    }

    /**
     * Add userResume
     *
     * @param \AppBundle\Entity\UserResume $userResume
     *
     * @return User
     */
    public function addUserResume(UserResume $userResume)
    {
        $this->userResumes[] = $userResume;

        return $this;
    }

    /**
     * Remove userResume
     *
     * @param \AppBundle\Entity\UserResume $userResume
     */
    public function removeUserResume(UserResume $userResume)
    {
        $this->userResumes->removeElement($userResume);
    }

    /**
     * Get userResumes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserResumes()
    {
        return $this->userResumes;
    }

    /**
     * Add userService
     *
     * @param \AppBundle\Entity\UserService $userService
     *
     * @return User
     */
    public function addUserService(\AppBundle\Entity\UserService $userService)
    {
        $this->userServices[] = $userService;

        return $this;
    }

    /**
     * Remove userService
     *
     * @param \AppBundle\Entity\UserService $userService
     */
    public function removeUserService(\AppBundle\Entity\UserService $userService)
    {
        $this->userServices->removeElement($userService);
    }

    /**
     * Get userServices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserServices()
    {
        return $this->userServices;
    }

    /**
     * Add userSkill
     *
     * @param \AppBundle\Entity\Skill $userSkill
     *
     * @return User
     */
    public function addUserSkill(Skill $userSkill)
    {
        $this->userSkills[] = $userSkill;

        return $this;
    }

    /**
     * Remove userSkill
     *
     * @param \AppBundle\Entity\Skill $userSkill
     */
    public function removeUserSkill(Skill $userSkill)
    {
        $this->userSkills->removeElement($userSkill);
    }

    /**
     * Get userSkills
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserSkills()
    {
        return $this->userSkills;
    }

    /**
     * Set userRate
     *
     * @param \AppBundle\Entity\UserRate $userRate
     *
     * @return User
     */
    public function setUserRate(\AppBundle\Entity\UserRate $userRate = null)
    {
        $this->userRate = $userRate;

        return $this;
    }

    /**
     * Get userRate
     *
     * @return \AppBundle\Entity\UserRate
     */
    public function getUserRate()
    {
        return $this->userRate;
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }
}
