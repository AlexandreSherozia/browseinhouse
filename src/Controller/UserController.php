<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\Handler\UserHandler;
use App\Service\UserManager;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

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

            $this->addFlash('success', 'home.registration.validation');

            return $this->redirectToRoute('index');
        }

        return $this->render('form/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Get user personnal infos for his profile landing page
     * @Route("/user-profile", name="user_profile")
     * @Security("has_role('ROLE_USER')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProfileData(Request $request, TokenStorageInterface $tokenStorage)
    {


        return $this->render('user/userprofile.html.twig');
    }


}