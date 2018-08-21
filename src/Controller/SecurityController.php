<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Security\AccountNotEnabledYet;
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
     * @Route("/logout", name="logout")
     */
    public function userLogout()
    {
    }
}