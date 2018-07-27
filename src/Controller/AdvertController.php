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


        $formHandler = new AdvertHandler($this->createForm(AdvertType::class, new Advert()),
            $request,
            $manager);

        /*$userId = $this->getUser()->getId();*/
        if ($formHandler->process(/*$userId*/)) {

            return $this->redirectToRoute("index");
        }

        return $this->render('form/createAdvertForm.html.twig',[
            'form' => $formHandler->getForm()->createView()
        ]);

    }

    public function showBuyingCategories()
    {

    }

}