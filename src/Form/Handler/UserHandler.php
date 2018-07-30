<?php

namespace App\Form\Handler;

use App\Service\ImageUploader;
use App\Service\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @param string $avatarDir
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
    public function process(string $type)
    {
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            if($type === 'new') {
                $this->onSuccessNew();

                return true;
            }
            elseif($type === 'edit') {
                $this->onSuccessEdit();

                return true;
            }

        }

        return false;
    }

    /**
     *
     */
    protected function onSuccessNew()
    {
        $userFormData = $this->form->getData();
        $this->userManager->addNewUserToDb($userFormData);
    }

    protected function onSuccessEdit()
    {
        $userFormData = $this->form->getData();
        $image = $userFormData->getAvatar();
        $filename = ImageUploader->upload();
        $this->userManager->UpdateUserIntoDb($userFormData);
    }
}