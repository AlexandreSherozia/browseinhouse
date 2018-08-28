<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Security\AccountNotEnabledYet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     *
     * @param AuthenticationUtils $authenticationUtils
     * @param AccountNotEnabledYet $accountNotEnabledYet
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * Login form page
     */
    public function userLogin(AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {

            return $this->redirectToRoute('index');
        }

        $form = $this->createForm(
            LoginType::class, [
                'email' => $authenticationUtils->getLastUsername()
            ]
        );

        $errorMessage = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            'form/login.html.twig', [
                'form' => $form->createView(),
                'errorMessage' => $errorMessage,
//            'accountNotEnabled' => $accountNotEnabledYet->getErrorMessage()
            ]
        );
    }


    /**
     * @param $token
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/account-validation-page/{token}", name="account_validation_page")
     */
    public function accountValidationPage($token)
    {
        return $this->render('form/account_validation.html.twig', [
            'token' => $token
        ]);
    }

    /**
     * @param $token
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/account-validatation-action/{token}", name="account_validation_action")
     */
    public function accountValidationAction($token, EntityManagerInterface $em)
    {
        //$user = new User();
        $userStatus = $this->getDoctrine()->getRepository(User::class)->findOneBy(['token' => $token]);
        if($userStatus) {
            $userStatus->addRole('ROLE_USER');
            $em->persist($userStatus);
            $em->flush();

            return $this->redirectToRoute('login');
        }
        return $this->redirectToRoute('waiting_for_confirmation');

    }

    /**
     * @Route("/logout", name="logout")
     */
    public function userLogout()
    {
    }
}
