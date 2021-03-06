<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Entity\Contact;
use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\Wishlist;
use App\Form\AdvertType;
use App\Form\ContactType;
use App\Form\Handler\AdvertHandler;
use App\Form\Handler\ContactHandler;
use App\Service\AdvertManager;
use App\Service\WishlistManager;
use App\Service\UserSubscriber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends Controller
{
    private $manager,
            $handler,
            $flashBag;

    public function __construct(AdvertManager $manager, AdvertHandler $handler,
                                FlashBagInterface $flashBag)
    {
        $this->manager = $manager;
        $this->handler = $handler;
        $this->flashBag = $flashBag;
    }

    /**
     * Allow user to create a new advert
     *
     * @Route("/create-advert",
     *     name="create_advert")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function addNewAdvert(Request $request)
    {
        $form = $this->createForm(AdvertType::class, new Advert());

        if ($this->handler->process($form, $request)) {

            return $this->redirectToRoute(
                'show_advert', [
                    'advertslug' => $this->handler->getForm()->getData()->getSlug()
                ]
            );
        }

        return $this->render(
            'form/createAdvertForm.html.twig', [
                'form' => $this->handler->getForm()->createView()
            ]
        );

    }

    /**
     * The page where an advert is fully displayed
     *
     * @Route("/show-advert/{advertslug}",
     *     name="show_advert")
     *
     * @param $advertslug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAdvert($advertslug)
    {
        $wishlists = $this->getDoctrine()->getRepository(Wishlist::class)->findAll();
        $advertData = $this->manager->getAdvertRepo()->findAdvertBySlug($advertslug);

        return $this->render(
            'advert/show_advert.html.twig', [
                'advertdata' => $advertData,
                'wishlists' => $wishlists
            ]
        );
    }

    /**
     * Allow an user to edit his own advert
     *
     * @Route("/edit-my-advert/{advertslug}", name="advert_edit")
     * @Security("has_role('ROLE_USER')")
     *
     * @param Request $request
     * @param string $advertslug The slug of the advert
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAdvert(Request $request, string $advertslug)
    {
        $advert = $this->manager->findAdvert($advertslug);
        $form = $this->createForm(AdvertType::class, $advert);

        if ($this->handler->process($form, $request)) {

            return $this->redirectToRoute(
                'show_advert', [
                    'advertslug' => $this->handler
                        ->getForm()->getData()->getSlug()
                ]
            );
        }

        return $this->render(
            'form/createAdvertForm.html.twig', [
                'form' => $this->handler->getForm()->createView()
            ]
        );
    }

    /**
     * Allow an user to delete his own advert
     *
     * @Route("/delete_advert/{id}",
     *     name="delete_advert")
     * @Security("has_role('ROLE_USER')")
     *
     * @param int $id the advert id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAdvert(int $id)
    {
        $this->manager->removeAdvert($id);

        return $this->redirectToRoute(
            'user_profile', [
                'pseudo' => $this->getUser()->getPseudo()
            ]
        );
    }

    /**
     * Display all adverts of a single section (with paginator)
     *
     * @Route("/section/{label}", name="show_adverts_by_section")
     *
     * @param string $label the section label
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAdvertsBySection(string $label, Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query = $this->manager->getAdvertsBySection($label),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'advert/show_adverts_by_section.html.twig', [
                'adverts' => $pagination,
                'sectionLabel' => $label,
                'advertCategories' => $this->manager->getCategoriesInSections()
            ]
        );
    }

    /**
     * Show all adverts of one category in a single section (whith paginator)
     *
     * @Route("/section/{sectionlabel}/category/{categorylabel}",
     *     name="filter_adverts_by_category_and_section")
     *
     * @param string $sectionlabel
     * @param string $categorylabel
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filterAdvertsByCategoryAndSection(string $sectionlabel,
                                                      string $categorylabel, Request $request
    )
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query = $this->manager->getAdvertsByCategoryAndSection(
                $sectionlabel, $categorylabel
            ),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'advert/show_adverts_by_category_and_section.html.twig', [
                'adverts' => $pagination,
                'category' => $categorylabel,
                'section' => $sectionlabel,
                /*'advertNb' => $this->manager
                ->getAdvertsNumberInCategoryAndSection($sectionlabel, $categorylabel)*/
            ]
        );
    }

    /**
     * Show all adverts in one category through all sections (with paginator)
     *
     * @Route("/category/{categorylabel}", name="filter_adverts_by_category")
     *
     * @param string $categorylabel
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filterAdvertsByCategory(string $categorylabel, Request $request)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query = $this->manager->getAdvertsByCategory($categorylabel),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'advert/show_adverts_by_category.html.twig', [
                'adverts' => $pagination,
                'category' => $categorylabel
            ]
        );
    }

    /**
     * Shows public infos and adverts list of a specific user by clicking on his
     * advert and receives ajax request in order to subscribing to this user
     *
     * @Route("/public-profile/{pseudo}", name="show_public_profile")
     *
     * @param Request $request
     * @param UserSubscriber $userSubscriber the subscription service
     * @param string $pseudo Pseudo we clicked on
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function userPublicProfile(UserSubscriber $userSubscriber,
                                      Request $request, string $pseudo)
    {
        $follower = $this->getUser();

        if ($response = $userSubscriber->subscriptionRequest($request)) {
            return new JsonResponse($response);
        }

        /*Si la relation existe entre ces deux utilisateurs, en arrivant sur la page,
        le bouton aura la classe appropriée*/
        $subscriptionStatus = $this->getDoctrine()
            ->getRepository(Subscription::class)
            ->subscriptionStatus($follower, $pseudo);

        $selectedUser = $this->getDoctrine()->getRepository(User::class)
            ->findOneByPseudo($pseudo);

        $userAdverts = $this->manager->getAdvertsByUser($selectedUser->getId());

        return $this->render(
            'user/user_publicprofile.html.twig', [
                'user_public' => $selectedUser,
                'user_adverts' => $userAdverts,
                'subscription' => $subscriptionStatus
            ]
        );
    }


    /**
     * Allows an user to contact another user through an advert by sending him
     * an email
     *
     * @Route("/advert/{slug}/user-contact", name="user_contact")
     *
     * @param string $slug
     * @param Request $request
     * @param ContactHandler $contactHandler
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     */
    public function contactUserFromAdvert(string $slug, Request $request,
                                          ContactHandler $contactHandler
    )
    {
        $advert = $this->getDoctrine()->getRepository(Advert::class)
            ->findOneBySlug($slug);

        $contacter = $this->getUser();

        $contactedUser = $this->getDoctrine()->getRepository(User::class)
            ->find($advert->getUser());

        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        if ($contactHandler->process($form, $request, $advert, $contacter, $contactedUser)) {

            $this->addFlash('success', 'advert.email.sent');

            return $this->redirectToRoute('show_advert', [
                    'advertslug' => $advert->getSlug(),
                    'advertdata' => $advert
                ]
            );
        }

        return $this->render(
            'form/user_contact.html.twig', [
                'form' => $form->createView(),
                'contactedUser' => $contactedUser,
                'advert' => $advert
            ]
        );
    }

    /**
     * Allow an user to add an advert to his personal wishlist
     *
     * @Route("/advert/{slug}/add-to-wishlist", name="add_to_wishlist")
     *
     * @param WishlistManager $manager
     * @param string $slug
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addAdvertToWishlist(WishlistManager $manager, string $slug)
    {
        $advertId = $this->getDoctrine()->getRepository(Advert::class)
            ->findOneBySlug($slug)->getId();

        $userId = $this->getUser()->getId();

        $manager->createNewWishlistRow($advertId, $userId);

        $wishlists = $this->getDoctrine()->getRepository(Wishlist::class)->findAll();

        return $this->render(
            'advert/show_advert.html.twig', [
                'advertdata' => $this->manager->getAdvertRepo()->findAdvertBySlug($slug),
                'wishlists' => $wishlists
            ]
        );
    }

    /**
     * Get user adverts from the user private profile page
     *
     * @Route("/adverts/{pseudo}", name="show_user_adverts")
     * @Security("has_role('ROLE_USER')")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserAdverts()
    {
        $user = $this->getUser();

        $userAdverts = $this->manager->getAdvertsByUser($user->getId());

        return $this->render(
            'user/userprofile_adverts.html.twig',
            [
                'advertList' => $userAdverts
            ]
        );
    }

    /**
     * Allow an user to delete an advert on his private profile page
     *
     * @Route("/user_delete_advert/{advert_id}", name="user_delete_advert")
     * @Security("has_role('ROLE_USER')")
     *
     * @param AdvertManager $advertManager
     * @param int $advert_id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function deleteUserAdverts(int $advert_id)
    {
        $this->manager->removeAdvert($advert_id);

        $user = $this->getUser();

        $userAdverts = $this->manager->getAdvertsByUser($user->getId());

        return $this->render('user/userprofile_adverts.html.twig', [
            'advertList' => $userAdverts,
        ]);
    }

    /**
     * Get user adverts from the user private profile page
     *
     * @Route("/wishlist/{pseudo}", name="show_user_wishlist")
     * @Security("has_role('ROLE_USER')")
     *
     * @param WishlistManager $manager
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAdvertsInWishlist(WishlistManager $manager)
    {
        $userId = $this->getUser()->getId();

        $advertList = $manager->getAdvertsInWishlist($userId);

        return $this->render('user/userprofile_wishlist.html.twig', ['advertList' => $advertList]);
    }
}

