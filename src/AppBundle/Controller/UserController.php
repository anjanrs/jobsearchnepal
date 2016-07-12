<?php
namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use AppBundle\Entity\User;
use AppBundle\Entity\UserType;

class UserController extends Controller
{
    /**
     * @Route("/user/register", name="user-register")
     */
    public function userRegisterAction(Request $request) {
        $user = new User();
        $form = $this->createForm('userregistration',$user)
            ->add('register_worker', 'submit', array('label' => 'Join As Worker'))
            ->add('register_employer', 'submit', array('label' => 'Join As Employer'));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $userManager = $this->get('jsn.manager.user');
            $user_type = $form->get('register_worker')->isClicked() ? 'worker': 'employer';
            $userManager->registerUser($user,$user_type);
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $securityContext = $this->get('security.context');
            $securityContext->setToken($token);
            return $this->redirectToRoute('homepage', array(), 301);
        }
        return $this->render('AppBundle:User:userRegister.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
?>