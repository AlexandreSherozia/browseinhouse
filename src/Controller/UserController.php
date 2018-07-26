<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Handler\UserHandler;
use App\Service\UserManager;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * Registration form page and process of a new user after submit
     * @Route("/register", name="register")
     * @param UserManager $userManager
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userRegistration(UserManager $userManager, Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $formHandler = new UserHandler($form, $request, $userManager);

        if ($formHandler->process()) {

            $this->addFlash('success', 'Congratulation, you have been registered !');

            return $this->redirectToRoute('index');
        }

        return $this->render('index/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}