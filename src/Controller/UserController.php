<?php

namespace App\Controller;

use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\Section;
use App\Form\UserType;
use App\Form\Handler\UserHandler;
use App\Service\AdvertManager;
use App\Service\ImageUploader;
use App\Service\UserManager;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param ImageUploader $imageUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userRegistration(UserManager $userManager, Request $request, ImageUploader $imageUploader, \Swift_Mailer $swift_Mailer)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $formHandler = new UserHandler($form, $request, $userManager, $imageUploader);

        if ($formHandler->process('new')) {

            $message = (new \Swift_Message("Your registration on B'N'H"))
                ->setFrom('browseinhouse@gmail.com')
                ->setTo($form->get('email')->getData())
                ->setBody(
                    $this->renderView(
                        'mail/registration.html.twig',
                        array('name' => $form->get('pseudo')->getData())
                    ),
                    'text/html'
                );

            $swift_Mailer->send($message);

            return $this->redirectToRoute('confirm');

        }

        return $this->render('form/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/confirm", name="confirm")
     */
    public function confirm()
    {
        return $this->render('mail/confirm.html.twig');
    }



    /****************************************************************************
     *                       USER PROFILE PERSONAL PANEL                        *
     ****************************************************************************/

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
     * @param UserManager $userManager
     * @param Request $request
     * @param ImageUploader $imageUploader
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editProfileData(UserManager $userManager, Request $request, ImageUploader $imageUploader)
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);

        $formHandler = new UserHandler($form, $request, $userManager, $imageUploader);

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

     /**
     * user avatar deletion
     * @Route("/avatar-delete", name="avatar_delete")
     * @Security("has_role('ROLE_USER')")
     * @param UserManager $userManager
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
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/my-subscription-list", name="my_subscription_list")
     */
    public function mySubscriptionList(UserManager $userManager)
    {
        $this->denyAccessUnlessGranted(['ROLE_USER']);

        $follower = $this->getUser();

        $subscription = $userManager->getSubscriptionList($follower);

        return $this->render('user/mySubscriptionList.html.twig', [
            'my_subscription_list' => $subscription
        ]);
    }



    /****************************************************************************
     *                              ADMINISTRATOR PANEL                         *
     ****************************************************************************/

    /**
     * show the list of all users for an admin user
     * @Route("/admin/user-list", name="user_list")
     * @Security("has_role('ROLE_ADMIN')")
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminShowUserList(UserManager $userManager)
    {
        $userList = $userManager->getUserList();

        return $this->render('admin/user_list.html.twig', [
            'userList' => $userList
        ]);
    }

    /**
     * delete an user from db in admin page
     * @Route("/admin/delete-user/{user_id}", name="delete_user")
     * @Security("has_role('ROLE_ADMIN')")
     * @param UserManager $userManager
     * @param int $user_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteUser(UserManager $userManager, int $user_id)
    {
        $userManager->removeUser($user_id);

        $this->addFlash('success', 'admin.deleteUser.validation');

        return $this->redirectToRoute('user_list');
    }

}