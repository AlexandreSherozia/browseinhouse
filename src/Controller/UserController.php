<?php

namespace App\Controller;


use App\Entity\Section;
use App\Service\AdvertManager;
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
    public function userRegistration(UserManager $userManager, Request $request, ImageUploader $imageUploader)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $formHandler = new UserHandler($form, $request, $userManager, $imageUploader);

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
     * show the list of all users for an admin user
     * @Route("/admin/user-list", name="user_list")
     * @Security("has_role('ROLE_ADMIN')")
     * @param UserManager $userManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserList(UserManager $userManager)
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteUser(UserManager $userManager, $user_id)
    {
        $userManager->removeUser($user_id);

        $this->addFlash('success', 'admin.deleteUser.validation');

        return $this->redirectToRoute('user_list');
    }

    /**
     * show the list of all adverts for an admin user
     * @Route("/admin/advert-list", name="advert_list")
     * @Security("has_role('ROLE_ADMIN')")
     * @param AdvertManager $advertManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAdvertList(AdvertManager $advertManager)
    {
        $sections = $advertManager->getAllSections();
        $categories = $advertManager->getAllCategories();
        $allAdverts = [];

        foreach($sections as $val)
        {
            /** @var Section $val */
            $id = $val->getId();
            $allAdverts[] = $advertManager->getAdvertsBysection($id);
        }

        dump($allAdverts);
        dump($sections);
        dump($categories);
        return $this->render('admin/advert_list.html.twig', [
            'advertList' => $allAdverts,
            'sections' => $sections,
            'categories' => $categories
        ]);
    }
}