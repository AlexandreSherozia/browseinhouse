<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 25/07/2018
 * Time: 06:42
 */

namespace App\Controller;


use App\Entity\Advert;
use App\Form\AdvertType;
use App\Form\Handler\AdvertHandler;
use App\Service\AdvertManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdvertController extends Controller
{


    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("create-advert/", name="create-advert")
     */
    public function addNewAdvert(Request $request,
                                 AdvertManager $manager)
    {

        $formHandler = new AdvertHandler($this->createForm(AdvertType::class,
            new Advert()),
            $request,
            $manager);


        if ($formHandler->process()) {

            return $this->redirectToRoute("index");
        }

        return $this->render('form/registerForm.html.twig',[
            'form' => $formHandler->getForm()->createView()
        ]);

    }

    public function showBuyingCategories()
    {

    }

}