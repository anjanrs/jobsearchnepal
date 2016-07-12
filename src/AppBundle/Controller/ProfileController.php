<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserAddress;
use AppBundle\Entity\UserResume;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ProfileController extends Controller
{
    /**
     * @Route("profile/contactdetails", name="prf-contactdetails")
     * @Template
     */
    public function editContactAction(Request $request)
    {
        $user = $this->getUser();
        $formPersonalInfo = $this->createForm('usercontact', $user);
        $formPersonalInfo->add('save_personal_info','submit',array('label'=>'Save'));
        $formPersonalInfo->handleRequest($request);
        if ($formPersonalInfo->isvalid()) {
            $user = $formPersonalInfo->getData();
            $this->get('jsn.manager.user')->saveUser($user);
        }

        $address = $user->getUserAddress();
        $formAddress = $this->createForm('useraddress', $address);
        $formAddress = $formAddress->add('save_address', 'submit', array('label'=>'save'));
        $formAddress->handleRequest($request);
        if ($formAddress->isValid()) {
            $address = $formAddress->getData();
            $address->setUser($user);
            $this->get('jsn.manager.user')->saveAddress($address);
        }

        return array(
            'formPersonalInfo' => $formPersonalInfo->createView(),
            'formAddress' => $formAddress->createView(),
        );
    }

    /**
     * @Route("profile/screenidentity", name="prf-screenidentity")
     * @Template
     */
    public function editscreenIdentityAction(Request $request)
    {
        $user = $this->getUser();
        $profile = $user->getUserProfile();
        $form = $this->createForm('userscreenidentity', $profile);
        $form->add('upload','button', array('label'=>'Choose Profile Picture'));
        $form->add('save_screen_identity', 'submit', array('label'=>'Save'));
        $form->handleRequest($request);
        if ($form->isValid()) {
            $profile = $form->getData();
            if($profile->getUser() === null){
                $profile->setUser($user);
            }
            $this->get('jsn.manager.user')->saveProfile($profile);
        }
        return array('form'=> $form->createView());
    }

    /**
     * @Route("profile/username", name="prf-username")
     * @Template
     */
    public function editUserNameAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add("_username",'text')
            ->add("_password",'password')
            ->add("save","submit",array("label"=>"Change Username"))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            if ($user) {
                $user_manager = $this->get("jsn.manager.user");
                if($user_manager->checkUserLogin($user->getUsername(), $form->get("_password")->getData())){
                    $user->setUsername($form->get("_username")->getData());
                    $user_manager->saveUser($user);
                }
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @param Request $request
     * @Route("profile/password", name="prf-password")
     * @Template
     */
    public function editUserPasswordAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add("current_password","password")
            ->add('password', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'required' => true,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add("save","submit",array("label"=>"Change Password"))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $user = $this->getUser();
            if($user){
                $user_manager = $this->get("jsn.manager.user");
                if($user_manager->checkUserLogin($user->getUsername(),$form->get("current_password")->getData())) {
                    $encoder = $this->get('security.password_encoder');
                    $encoded = $encoder->encodePassword($user, $form->get("password")->getData());
                    $user->setPassword($encoded);
                    $user_manager->saveUser($user);
                }
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

}