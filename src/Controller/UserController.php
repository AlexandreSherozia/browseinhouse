<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditionUserType;
use App\Form\RegistrationUserType;
use App\Form\UserType;
use App\Form\Handler\UserHandler;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * Registration form page and process of a new user after submit
     *
     * @Route("/register", name="register")
     *
     * @param UserHandler $userHandler
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userRegistration(UserHandler $userHandler, Request $request)
    {
        $user = new User();

        $form = $this->createForm(RegistrationUserType::class, $user);

        if ($userHandler->process('new', $form, $request)) {

           return $this->redirectToRoute('waiting_for_confirmation');
        }

        return $this->render(
            'form/register.html.twig', ['form' => $form->createView()]
        );
    }

    /**
     * Inform that a mail has been sent after registration
     *
     * @Route("/waiting-for-confirmation", name="waiting_for_confirmation")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function confirm()
    {
        return $this->render('mail/confirm.html.twig');
    }

    /**
     * Get user personnal infos for his profile landing page
     *
     * @Route("/user-profile/{pseudo}", name="user_profile")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showProfileData()
    {
        return $this->render('user/userprofile.html.twig');
    }

    /**
     * User avatar deletion
     *
     * @Route("/avatar-delete", name="avatar_delete")
     * @Security("has_role('ROLE_USER')")
     *
     * @param UserManager $userManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAvatar(UserManager $userManager)
    {
        $user = $this->getUser();

        $userManager->removeAvatar($user->getId());

        return $this->redirectToRoute('user_profile', [
            'pseudo' => $user->getPseudo()
        ]);
    }

    /**
     * Let user modify or add infos in his personnal infos panel
     *
     * @Route("/edit-profile/{pseudo}", name="edit_profile")
     *
     * @Security("has_role('ROLE_USER')")
     *
     * @param UserHandler $userHandler
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProfileData(UserHandler $userHandler, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditionUserType::class, $user);

        if ($userHandler->process('edit', $form, $request)) {

            $this->addFlash('success', 'userprofile.edit.validation');

            return $this->redirectToRoute(
                'user_profile',
                [
                    'pseudo' => $user->getPseudo()
                ]
            );
        }

        return $this->render('form/editprofile.html.twig', ['form' => $form->createView()]);
    }



    /**
     * @Route("/my-subscription-list", name="my_subscription_list")
     *
     * @param UserManager $userManager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mySubscriptionList(UserManager $userManager)
    {
        $this->denyAccessUnlessGranted(['ROLE_USER']);

        $follower = $this->getUser();

        $subscription = $userManager->getSubscriptionList($follower);

        return $this->render(
            'user/mySubscriptionList.html.twig',
            [
                'my_subscription_list' => $subscription
            ]
        );
    }
}
