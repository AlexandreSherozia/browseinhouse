<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 25/07/2018
 * Time: 14:21
 */

namespace App\Form\Handler;


use App\Service\AdvertManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AdvertHandler
{
    protected   $form,
                $advert,
                $request,
                $advertManager;

    /**
     * AdvertHandler constructor.
     * @param $form
     * @param $request
     */
    public function __construct(Form $form, Request $request)
    {
        $this->form             = $form;
        $this->request          = $request;
        $this->advertManager    = new AdvertManager();

    }

    public function process()
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() and $this->form->isValid()) {
          
        }

        return false;

    }

    public function getAdvert()
    {
        return $this->advert;
    }

    public function getForm()
    {
        return $this->form;
    }

    protected function onSuccess()
    {
        $advert = $this->form->getData();
        $this->advert = $this->advertManager->persist($advert);

    }


}