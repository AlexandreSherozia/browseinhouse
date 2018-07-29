<?php

namespace App\Controller;


use App\Entity\Advert;
use App\Form\AdvertType;
use App\Form\Handler\AdvertHandler;
use App\Service\AdvertManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdvertController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/create-advert", name="create_advert")
     */
    public function addNewAdvert(Request $request, AdvertManager $manager)
    {

        $this->denyAccessUnlessGranted(['ROLE_USER']);


        $formHandler = new AdvertHandler($this->createForm(AdvertType::class, new Advert()),$request,$manager);

        if ($formHandler->process()) {

            return $this->redirectToRoute("show_buying_categories");
        }

        return $this->render('form/createAdvertForm.html.twig',[
            'form' => $formHandler->getForm()->createView()
        ]);

    }

    /**
     * Penser à modifier en "showCategoriesBySection" et rajouter en parametre
     * "sectionId", ce qui evitera la creation des methodes similaires
     * pour chaque section
     *
     * @Route("/Buy", name="show_buying_categories")
     */
    public function showBuyingCategories(AdvertManager $advertManager) //
    {
        return $this->render('advert/show_buying_categories.html.twig', [
            'buyingCategories' => $advertManager->getBuyingCategories()
        ]);
    }

    /**
     * @Route("/Buy/{id}", name="filter_adverts_by_category")
     */
    public function filterAdvertsByCategory(AdvertManager $advertManager, $id)
    {

        return $this->render('advert/filter_adverts_by_category.html.twig', [
            'filteredAdvertsByCategory' => $advertManager->getAdvertsByCategory($id),
            'buyingCategories'          => $advertManager->getBuyingCategories()
        ]);
    }

    /**
     * @param $id
     * @Route("/advert/{id}", name="show_advert")
     */
    public function showAdvert(AdvertManager $advertManager, $id)
    {
        return $this->render('advert/show_advert.html.twig', [
            'advert'  => $advertManager->showAdvert($id)
        ]);
    }

}