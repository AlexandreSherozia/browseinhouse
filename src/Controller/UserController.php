<?php

namespace App\Controller;

use App\Service\ImageUploader;
use App\Entity\User;
use App\Form\Handler\UserHandler;
use App\Service\UserManager;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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

        if ($formHandler->process('new')) {

            $this->addFlash('success', 'login.registration.validation');

            return $this->redirectToRoute('login');
        }

        return $this->render('form/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Get user personnal infos for his profile landing page
     * @Route("/user-profile/{pseudo}", name="user_profile")
     * @Security("has_role('ROLE_USER')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProfileData()
    {
        return $this->render('user/userprofile.html.twig');
    }

    /**
     * let user modify or add infos in his personnal infos panel
     * @Route("/edit-profile/{pseudo}", name="edit_profile")
     * @Security("has_role('ROLE_USER')")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editProfileData(UserManager $userManager, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $formHandler = new UserHandler($form, $request, $userManager);

        if ($formHandler->process('edit')) {

            $this->addFlash('success', 'userprofile.edit.validation');

            return $this->redirectToRoute('user_profile', [
                'pseudo' => $user->getPseudo()
            ]);
        }

        return $this->render('form/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}