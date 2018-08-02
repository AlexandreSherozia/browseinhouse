<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 25/07/2018
 * Time: 14:21
 */

namespace App\Form\Handler;


use App\Entity\Advert;
use App\Entity\User;
use App\Service\AdvertManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AdvertHandler
{
    protected   $form,
                $advert,
                $advertManager;


    /**
     * AdvertHandler constructor.
     * @param $form
     * @param $request
     */
    public function __construct(Form $form, Request $request, AdvertManager $advertManager)
    {
        $this->form             = $form;
        $this->request          = $request;
        $this->advertManager    = $advertManager;


    }

    public function process()
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->onSubmitted();   //onSuccess

            return true;
        }

        return false;

    }


    /**
     * @return Form
     * Advert controller doesn't have "form" anymore to build the form by
     * "form->createview", "getFrom" method provides him with it
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Protected method, gets filled form data and
     *  "myPersist" method, which persists and flushes by native Doctrine methods
     *  of AdvertManager
     */
    protected function onSubmitted() //onSuccess
    {

        $advert = $this->form->getData();


        //$slug = $advert->getSlug();

        $this->advert = $this->advertManager->myPersist($advert);

    }


}