<?php

namespace App\Form\Handler;

use App\Service\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class UserHandler
{
    protected   $form,
                $user ,
                $request,
                $userManager;

    public function __construct(Form $form, Request $request, UserManager $userManager)
    {
        $this->form             = $form;
        $this->request          = $request;
        $this->userManager    = $userManager;
    }

    public function process()
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->onSuccess();

            return true;
        }

        return false;
    }

    public function getUser()
    {
        return $this->user;
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
    protected function onSuccess()
    {
        $user = $this->form->getData();
        $this->user = $this->userManager->myPersist($user);
    }


}