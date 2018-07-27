<?php

namespace App\Form\Handler;

use App\Service\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class UserHandler
{
    protected   $form,
                $request,
                $userManager;

    /**
     * UserHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param UserManager $userManager
     */
    public function __construct(Form $form, Request $request, UserManager $userManager)
    {
        $this->form             = $form;
        $this->request          = $request;
        $this->userManager      = $userManager;
    }

    /**
     * form submission verification
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->onSuccess();

            return true;
        }

        return false;
    }

    /**
     *
     */
    protected function onSuccess()
    {
        $userFormData = $this->form->getData();
        $this->userManager->addNewUserToDb($userFormData);
    }
}