<?php

namespace App\Controller;

use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{
    /**
     * Login form page
     * @Route("/login", name="login")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userLogin(Request $request, AuthenticationUtils $authenticationUtils)
    {
        if ($this->getUser()) {
            return  $this->redirectToRoute('index');
        }

        $form = $this->createForm(LoginType::class, [
            'email' => $authenticationUtils->getLastUsername()
        ]);

        $errorMessage = $authenticationUtils->getLastAuthenticationError();

        return $this->render('form/login.html.twig', [
            'form' => $form->createView(),
            'errorMessage' => $errorMessage
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
    }
}