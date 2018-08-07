<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\User;
use App\Entity\Advert;
use App\Entity\Section;
use App\Form\ContactType;
use App\Form\Handler\ContactHandler;
use App\Form\UserType;
use App\Form\Handler\UserHandler;
use App\Service\AdvertManager;
use App\Service\ImageUploader;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * show public infos on a specific user
     * @Route("/public-profile/{pseudo}", name="show_public_profile")
     *
     */
    public function userPublicProfile(AdvertManager $manager, $pseudo)
    {
        $selectedUser = $this->getDoctrine()->getRepository(User::class)->findOneByPseudo($pseudo);
        $userAdverts = $manager->getAdvertsByUser($selectedUser->getId());

        return $this->render('user/user_publicprofile.html.twig', [
            'user_public'  => $selectedUser,
            'user_adverts' => $userAdverts
        ]);
    }

    /**
     * Allow an user to contact an other through an advert by sending him an email
     * @Route("/advert/{slug}/user-contact", name="user_contact")
     * @Security("has_role('ROLE_USER')")
     */
    public function contactUserFromAdvert($slug, Request $request , ContactHandler $contactHandler)
    {
        $advert = $this->getDoctrine()->getRepository(Advert::class)->findOneBySlug($slug);
        $contacter = $this->getUser();
        $contactedUser = $this->getDoctrine()->getRepository(User::class)->find($advert->getUser());

        $contact = new Contact();
        $contact->setContactingEmail($contacter->getEmail());
        $contact->setContactedEmail($contactedUser->getEmail());
        $contact->setAdvertSlug($slug);
        $contact->setAdvertTitle($advert->getTitle());
        $contact->setContactedPseudo($contactedUser->getPseudo());
        $contact->setContactingPseudo($contacter->getPseudo());

        $form = $this->createForm(ContactType::class, $contact);

        if ($contactHandler->process($form, $request)) {

            $this->addFlash('success', 'contact.validation');

            return $this->redirectToRoute('show_advert', [
                'advertslug' => $advert->getSlug(),
                'advertdata'  => $advert,
                'contact' => $contact
            ]);
        }

        return $this->render('form/user_contact.html.twig',[
            'form' => $form->createView(),
            'contacter' => $contacter,
            'advert' => $advert
        ]);
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
     * Get user adverts
     * @Route("/adverts/{pseudo}", name="show_user_adverts")
     * @Security("has_role('ROLE_USER')")
     * @param AdvertManager $advertManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserAdverts(AdvertManager $advertManager)
    {
        $user = $this->getUser();

        /** @var Section $section */
        foreach($advertManager->getAllSections() as $section){
            $allSections[] = $section->getLabel();
        }

        $userAdverts = $advertManager->getAdvertsByUser($user->getId());

        return $this->render('user/userprofile_adverts.html.twig', [
            'advertList' => $userAdverts,
        ]);
    }

    /**
     * allow an user to delete an advert on his profile page
     * @Route("/user_delete_advert/{advert_id}", name="user_delete_advert")
     * @Security("has_role('ROLE_USER')")
     * @param AdvertManager $advertManager
     * @param $advert_id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserAdverts(AdvertManager $advertManager, $advert_id)
    {
        $advertManager->removeAdvert($advert_id);

        $user = $this->getUser();

        /** @var Section $section */
        foreach($advertManager->getAllSections() as $section){
            $allSections[] = $section->getLabel();
        }

        $userAdverts = $advertManager->getAdvertsByUser($user->getId());

        return $this->render('user/userprofile_adverts.html.twig', [
            'advertList' => $userAdverts,
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

    /**
     * show the list of all adverts for an admin user
     * @Route("/admin/advert-list", name="advert_list")
     * @Security("has_role('ROLE_ADMIN')")
     * @param AdvertManager $advertManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function adminShowAdvertList(AdvertManager $advertManager)
    {
        $allAdverts = $advertManager->getAllAdvertsInfos();
        /** @var Section $section */
        foreach($advertManager->getAllSections() as $section){
            $allSections[] = $section->getLabel();
        }

        return $this->render('admin/advert_list.html.twig', [
            'advertList' => $allAdverts,
            'sections' => $allSections
        ]);
    }

    /**
     * delete an advert from db in admin page
     * @Route("/admin/delete-advert/{advert_id}", name="admin_delete_advert")
     * @Security("has_role('ROLE_ADMIN')")
     * @param AdvertManager $advertManager
     * @param int $advert_id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function adminDeleteAdvert(AdvertManager $advertManager, int $advert_id)
    {
        $advertManager->removeAdvert($advert_id);

        $this->addFlash('success', 'admin.deleteAdvert.validation');

        return $this->redirectToRoute('advert_list');
    }

}