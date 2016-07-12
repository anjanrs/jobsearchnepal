<?php

namespace AppBundle\Service;


use AppBundle\Form\User\UserAddress;
use Doctrine\Bundle\DoctrineBundle\Registry;
use AppBundle\Entity\User;
use AppBundle\Entity\UserProfile;
use AppBundle\Entity\Role;
use AppBundle\Entity\UserType;
use AppBundle\Entity\UserResume;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use AppBundle\Security\UserProvider;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserManager
{
    private $doctrine;
    private $encoder;
    private $userProvider;

    public function __construct(Registry $doctrine, UserPasswordEncoderInterface $encoder)
    {
        $this->doctrine = $doctrine;
        $this->encoder = $encoder;
    }

    public function setUserProvider(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }

    public function registerUser(User &$user,$user_type)
    {
        $user_role = '';
        if ($user_type == "worker") {
            $user_role =  'ROLE_WORKER';
        } else if ($user_type == "employer"){
            $user_role = 'ROLE_EMPLOYER';
        }
        $em = $this->doctrine->getManager();
        $repository=  $em->getRepository('AppBundle:Role');
        $role = $repository->findOneBy(array('role' => $user_role));
        $user->addUserRole($role);
        $user->setUserName($user->getEmail());
        $user->setIsActive(1);
        $user->setFirstname($user->getFullname());
        $user->setLastname($user->getFullname());
        $user->setUserType($this->getUserType($user_type));
        $encoded = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setPassword($encoded);
        $em->persist($user);
        $em->flush();
    }

    public function getUserType($user_type)
    {
       $entUserType = $this->doctrine->getRepository('AppBundle:UserType')->findOneBy(
            array("userType" => $user_type)
        );

        return $entUserType;
    }

    public function checkUserLogin($username, $password)
    {
        $valid_user = null;
        if (!empty($username) && !empty($password)) {
            $user = $this->userProvider->loadUserByUsername($username);
            #$encoded_password = $this->encoder->encodePassword($user, $password);
            if($this->encoder->isPasswordValid($user,$password)){
                $valid_user = $user;
            }
        }

        return $valid_user;
    }

    public function checkEmployerLogin($username, $password)
    {
        $valid_user = $this->checkUserLogin($username, $password);
        if (!$valid_user->hasRole("ROLE_EMPLOYER")) {
            $valid_user = null;
        }
        return  $valid_user;
    }

    public function saveUser(User $user)
    {
        $em = $this->doctrine->getManager();
        $em->persist($user);
        $em->flush();
    }

    public function saveAddress(UserAddress $address)
    {
        $em = $this->doctrine->getManager();
        $em->persist($address);
        $em->flush();
    }

    public function saveProfile(UserProfile $profile)
    {
        $em = $this->doctrine->getManager();
        $em->persist($profile);
        $em->flush();
    }

    public function addResume(UserResume $resume)
    {
        $em = $this->doctrine->getManager();
        $em->persist($resume);
        $em->flush();
    }

    public function searchWorkers($searchData) {
        $append_where = "";
        if (isset($searchData) && !empty($searchData)) {

            if (isset($searchData["searchKey"]) && !empty($searchData["searchKey"])) {
                $append_where = " and
                    (
                        us.title like '%" . $searchData["searchKey"] . "%' or
                        us.description like '%" . $searchData["searchKey"] . "%'  or
                        jc.title like '%" . $searchData["searchKey"] . "%'  or
                        jc.description like '%" . $searchData["searchKey"] . "%'  or
                        ua.city like '%" . $searchData["searchKey"] . "%'  or
                        ua.district like '%" . $searchData["searchKey"] . "%'  or
                        ua.zone like '%" . $searchData["searchKey"] . "%'  or
                    ) ";
            }
            if (isset($searchData["jobCategory"]) && !empty($searchData["jobCategory"])) {
                $objJobCategory = $searchData["jobCategory"];
                $append_where = " and us.category_id = " . $objJobCategory->getId();
            }

            if (isset($searchData["location"]) && !empty($searchData["location"])) {
                $append_where = " and  (
                    ua.city like '%" . $searchData["location"] . "%' or
                    ua.district like '%" . $searchData["location"] . "%' or
                    ua.zone like '%" . $searchData["location"] . "%'
                 )";
            }

            if (isset($searchData["rateType"]) && !empty($searchData["jobType"]) &&
                isset($searchData["rate"]) && !empty($searchData["rate"])) {
                $objRateType = $searchData["rateType"];
                $append_where = " and
                    ur.rate_type_id = " . $objRateType->getId() . " and
                    ur.rate <= " . $searchData["rate"];
            }
        }
        $query = "select * from app_users u
            left join user_service us on u.id = us.user_id
            left join user_address ua on u.id = ua.user_id
            left join user_rate ur on u.id = ur.user_id
            left join job_category jc on us.category_id = jc.id
            where 1=1 " . $append_where ;
        $workers = $this->doctrine->getManager()
            ->getConnection()
            ->prepare($query);
        $workers->execute();
        return $workers->fetchAll();
    }
}