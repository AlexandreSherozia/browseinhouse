<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Form\AdvertType;
use App\Form\Handler\AdvertHandler;
use App\Service\AdvertManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
    public function addNewAdvert(Request $request)
    {

        $this->denyAccessUnlessGranted(['ROLE_USER']);


        $formHandler = new AdvertHandler($this->createForm(AdvertType::class, new Advert()), $request, $this->manager);

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
     * @Route("/delete-advert/{id}", name="delete_advert")
     */
    public function deleteAdvert($id)
    {
        $this->manager->removeAdvert($id);

        return $this->redirectToRoute('user_profile', [
            'pseudo'=> $this->getUser()->getPseudo()
        ]);
    }

    /**
     * @Route("/section/{label}", name="show_adverts_by_section")
     */
    public function showAdvertsBySection($label)
    {
        return $this->render('advert/show_adverts_by_section.html.twig', [
            'advertsBySection' => $this->manager->getAdvertsBySection($label)
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



}