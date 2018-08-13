<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Entity\Contact;
use App\Entity\User;
use App\Form\AdvertType;
use App\Form\ContactType;
use App\Form\Handler\AdvertHandler;
use App\Form\Handler\ContactHandler;
use App\Service\AdvertManager;
use App\Service\AdvertPhotoUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends Controller
{
    /**
     * @var AdvertManager
     */
    private $manager;

    public function __construct(AdvertManager $manager)
    {
        $this->manager = $manager;
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
                                        $advertPhotoUploader);

        if ($formHandler->process()) {

            dump($formHandler);
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
    public function editAdvert(Request $request, $advertslug)
    {
        $this->denyAccessUnlessGranted(['ROLE_USER']);

        $advert = $this->manager->findAdvert($advertslug);
        $advertHandler = new AdvertHandler($this->createForm(AdvertType::class, $advert),
            $request,
            $this->manager);

        if ($advertHandler->process()) {
            return $this->redirectToRoute('show_advert',[
                'advertslug' => $advertHandler
                    ->getForm()
                    ->getData()
                    ->getSlug()

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
            dump($pagination);
        return $this->render('advert/show_adverts_by_section.html.twig', [
            'advertsBySection' => $pagination
        ]);
    }

    /**
     * @Route("/section/{sectionlabel}/category/{categorylabel}", name="filter_adverts_by_category_and_section")
     */
    public function filterAdvertsByCategoryAndSection($sectionlabel, $categorylabel)
    {
        return $this->render('advert/filter_adverts_by_category.html.twig', [
            'filteredAdvertsByCategory' => $this->manager->getAdvertsByCategoryAndSection($sectionlabel, $categorylabel)
        ]);
    }

    /**
     * @Route("/category/{id}", name="filter_adverts_by_category")
     */
    public function filterAdvertsByCategory($id)
    {

        return $this->render('advert/filter_adverts_by_category.html.twig', [
            'filteredAdvertsByCategory' => $this->manager->getAdvertsByCategory($id)/*,
            'buyingCategories'          => $advertManager->getAdvertsBysection($sectionid)*/
        ]);
    }


    /**
     * Allow an user to contact an other through an advert by sending him an email
     * @Route("/advert/{slug}/user-contact", name="user_contact")
     * @Security("has_role('ROLE_USER')")
     * @param string $slug
     * @param Request $request
     * @param ContactHandler $contactHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
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
                'advertslug' => $advert->getSlug(),
                'advertdata'  => $advert
            ]);
        }

        return $this->render('form/user_contact.html.twig',[
            'form' => $form->createView(),
            'contactedUser' => $contactedUser,
            'advert' => $advert
        ]);
    }


    /**
     * show public infos and adverts list of a specific user by clicking on his advert
     * @Route("/public-profile/{pseudo}", name="show_public_profile")
     * @param AdvertManager $manager
     * @param string $pseudo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function userPublicProfile(AdvertManager $manager, string $pseudo)
    {
        $selectedUser = $this->getDoctrine()->getRepository(User::class)->findOneByPseudo($pseudo);
        $userAdverts = $manager->getAdvertsByUser($selectedUser->getId());

        return $this->render('user/user_publicprofile.html.twig', [
            'user_public'  => $selectedUser,
            'user_adverts' => $userAdverts
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

