<?php

namespace App\Controller;


use App\Entity\User;
use App\User\UserManager;
use App\User\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{

    /**
     * @Route("/register", name="register")
     * @param UserManager $userManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userRegistration(UserManager $userManager, Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            # Enregistrement de l'utilisateur
            $message = $userManager->addNewUser($user);

            $this->addFlash('success', $message);

            # Redirection
            return $this->redirectToRoute('index');
        }

        return $this->render('index/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}