<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Contact;
use App\Entity\Subscription;
use App\Entity\User;
use App\Form\AdvertType;
use App\Form\ContactType;
use App\Form\Handler\AdvertHandler;
use App\Form\Handler\ContactHandler;
use App\Service\AdvertManager;
use App\Service\AdvertPhotoUploader;
use App\Service\UserSubscriber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends Controller
{
    /**
     * @var AdvertManager
     */
    private $manager, $flashBag;

    public function __construct(AdvertManager $manager, FlashBagInterface $flashBag)
    {
        $this->manager = $manager;
        $this->flashBag= $flashBag;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create-advert", name="create_advert")
     */
    public function addNewAdvert(Request $request, AdvertPhotoUploader $advertPhotoUploader)
    {

        $this->denyAccessUnlessGranted(['ROLE_USER']);


        $formHandler = new AdvertHandler($this->createForm(AdvertType::class, new Advert()),
                                        $request,
                                        $this->manager,
                                        $advertPhotoUploader, $this->flashBag);

        if ($formHandler->process()) {

            return $this->redirectToRoute('show_advert' ,
                ['advertslug' => $formHandler->getForm()->getData()->getSlug() ]);

        }

        return $this->render('form/createAdvertForm.html.twig',[
            'form' => $formHandler->getForm()->createView()
        ]);

    }

    /**
     * @param $slug
     * @Route("/show-advert/{advertslug}", name="show_advert")
     */
    public function showAdvert($advertslug)
    {
        return $this->render('advert/show_advert.html.twig', [
            'advertdata'  => $this->manager->getAdvertRepo()->findAdvertBySlug($advertslug)

        ]);
    }

    /**
     * @param Request $request
     * @param AdvertManager $manager
     * @Route("/edit-my-advert/{advertslug}", name="advert_edit")
     */
    public function editAdvert(Request $request, $advertslug, AdvertPhotoUploader $advertPhotoUploader)
    {
        $this->denyAccessUnlessGranted(['ROLE_USER']);

        $advert = $this->manager->findAdvert($advertslug);
        $advertHandler = new AdvertHandler($this->createForm(AdvertType::class, $advert),
            $request,
            $this->manager, $advertPhotoUploader, $this->flashBag);

        if ($advertHandler->process()) {
            return $this->redirectToRoute('show_advert',[
                'advertslug' => $advertHandler
                    ->getForm()->getData()->getSlug()

            ] );
        }

        return $this->render('form/createAdvertForm.html.twig', [
            'form' => $advertHandler->getForm()->createView()
        ]);
    }


    /**
     * @param $id
     * @Security("has_role('ROLE_USER')")
     * @Route("/delete_advert/{id}", name="delete_advert")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAdvert(int $id)
    {
        $this->manager->removeAdvert($id);

        return $this->redirectToRoute('user_profile', [
            'pseudo'=> $this->getUser()->getPseudo()
        ]);
    }

    /**
     * @Route("/section/{label}", name="show_adverts_by_section")
     */
    public function showAdvertsBySection($label, Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query = $this->manager->getAdvertsBySection($label),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('advert/show_adverts_by_section.html.twig', [
            'adverts' => $pagination,
            'sectionLabel' => $label,
            'advertCategories' => $this->manager->getCategoriesInSections()
        ]);
    }

    /**
     * @Route("/section/{sectionlabel}/category/{categorylabel}", name="filter_adverts_by_category_and_section")
     */
    public function filterAdvertsByCategoryAndSection($sectionlabel, $categorylabel, Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query = $this->manager->getAdvertsByCategoryAndSection($sectionlabel, $categorylabel),
            $request->query->getInt('page', 1),
            10
        );


        return $this->render('advert/show_adverts_by_category_and_section.html.twig', [
            'adverts' => $pagination,
            'category' => $categorylabel,
            'section' => $sectionlabel,
            'advertNb' => $this->manager->getAdvertsNumberInCategoryAndSection($sectionlabel, $categorylabel)
        ]);
    }

    /**
     * @Route("/category/{categorylabel}", name="filter_adverts_by_category")
     */
    public function filterAdvertsByCategory($categorylabel, Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query = $this->manager->getAdvertsByCategory($categorylabel),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('advert/show_adverts_by_category.html.twig', [
            'adverts'   => $pagination,
            'category'  => $categorylabel
        ]);
    }


    /**
     * Shows public infos and adverts list of a specific user by clicking on his advert and
     * recieves ajax subscribing request
     * @param Request $request
     * @param UserSubscriber $userSubscriber
     * @param string $pseudo
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/public-profile/{pseudo}", name="show_public_profile")
     */
    public function userPublicProfile(Request $request, UserSubscriber $userSubscriber, string $pseudo)
    {
        $follower   = $this->getUser();

        if ($response = $userSubscriber->subscriptionRequest($request)) {
            return new JsonResponse($response);
        }

        //Si la relation existe entre ces deux utilisateurs, en arrivant sur la page, le bouton aura la classe appropriée
        $subscriptionStatus = $this->getDoctrine()->getRepository(Subscription::class)->subscriptionStatus($follower, $pseudo);
        /*dump($subscriptionStatus);*/
        $selectedUser = $this->getDoctrine()->getRepository(User::class)->findOneByPseudo($pseudo);
        $userAdverts = $this->manager->getAdvertsByUser($selectedUser->getId());

        return $this->render('user/user_publicprofile.html.twig', [
            'user_public'  => $selectedUser,
            'user_adverts' => $userAdverts,
            'subscription' => $subscriptionStatus
        ]);
    }


    /**
     * Allows an user to contact another user through an advert by sending him an email *
     * @param string $slug
     * @param Request $request
     * @param ContactHandler $contactHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/advert/{slug}/user-contact", name="user_contact")
     */
    public function contactUserFromAdvert(string $slug, Request $request , ContactHandler $contactHandler)
    {
        $advert = $this->getDoctrine()->getRepository(Advert::class)->findOneBySlug($slug);
        $contacter = $this->getUser();
        $contactedUser = $this->getDoctrine()->getRepository(User::class)->find($advert->getUser());

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        if ($contactHandler->process($form, $request, $advert, $contacter, $contactedUser)) {

            $this->addFlash('success', 'advert.email.sent');

            return $this->redirectToRoute('show_advert', [
                'advertslug'    => $advert->getSlug(),
                'advertdata'    => $advert
            ]);
        }

        return $this->render('form/user_contact.html.twig',[
            'form' => $form->createView(),
            'contactedUser' => $contactedUser,
            'advert' => $advert
        ]);
    }


    /****************************************************************************
     *                       USER PROFILE PERSONAL PANEL                        *
     ****************************************************************************/

    /**
     * Get user adverts from the user private profile page
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
     * allow an user to delete an advert on his private profile page
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

