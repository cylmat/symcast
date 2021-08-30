<?php

namespace SecurityAuth\Controller;

use SecurityAuth\Entity\User;
use SecurityAuth\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/** 
 * Can use IsGranted("ROLE_ADMIN_COMMENT") annotation
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="security_login")
     */
    public function login(
        Request $request, 
        AuthenticationUtils $utils,
        CsrfTokenManagerInterface $csrfManager
    ) {
        $error = $utils->getLastAuthenticationError(); // onAuthenticationFailure
        $lastUsername = $utils->getLastUsername();

        // Need Translation bundle
        return $this->render('@SecurityAuth/login.html.twig', [
            'last_username' => $lastUsername, //to pre-fill form, have to be done manually
            'error'         => $error,
            'csrf_token_authenticating' => $csrfManager->getToken('authenticating')
        ]);
    }

    /**
     * -> Manual authentication
     * After registration, I want to instantly authenticate the new user.
     * 
     * @Route("/register", name="security_register")
     */
    public function register(
        Request $request, 
        AuthenticationUtils $utils, 
        CsrfTokenManagerInterface $csrfManager,
        UserPasswordEncoderInterface $passwordEncoder,

        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $formAuthenticator
    ) {
         // TODO - use Symfony forms & validation
         // Register new User
         if ($request->isMethod('POST')) {
            $user = new User();
            $user->setEmail($request->request->get('email'));
            $user->setFirstName('Registered');

            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $request->request->get('password')
            ));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);

die('dont insert user for now...');
            
            $em->flush();

            /*
              Registered User, but still anonymous:
                return $this->redirectToRoute('security_account');
            */

            // Instead, I want to be logged in right after registration!
            /*
                Solve two problems - 
                    We want to automatically authenticate the user after registration 
                    And redirect them intelligently - 
                
                Use LoginFormAuthenticator class to authenticate the user and redirect by using its onAuthenticationSuccess()
            */
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main' // name of the firewall
            );
        }

        return $this->render('@SecurityAuth/register.html.twig', [
            'last_username' =>  $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError(),
            'csrf_token_authenticating' => $csrfManager->getToken('authenticating')
        ]);
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        // authenticators run automatically at the beginning of every request, before the controllers
        throw new \Exception('Will be intercepted before getting here');
    }

    /**
     * @Route("/guest", name="security_guest")
     */
    public function guest()
    {
        return $this->render('@SecurityAuth/guest.html.twig', []);
    }

    /** 
     * @Route("/admin", name="security_admin") 
     */
    public function admin()
    {
        return $this->render("@SecurityAuth/admin.html.twig");
    }

    /** 
     * @Route("/adm2", name="security_adm2") 
     */
    public function admin2()
    {
        // redirect in not connected
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
     
        return $this->render("@SecurityAuth/admin.html.twig");
    }

    /** 
     * @IsGranted("ROLE_ADMIN")
     * @Route("/adm3", name="security_adm3")
     */
    public function admin3()
    {
        // use annotation IsGranted

        return $this->render("@SecurityAuth/admin.html.twig");
    }
}