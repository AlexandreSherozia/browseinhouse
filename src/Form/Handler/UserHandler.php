<?php

namespace App\Form\Handler;

use App\Entity\User;
use App\Service\ImageUploader;
use App\Service\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class UserHandler
{
    protected   $form,
                $request,
                $userManager,
                $imageUploader,
                $currentAvatar;

    /**
     * UserHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param UserManager $userManager
     * @param string $avatarDir
     */
    public function __construct(Form $form, Request $request, UserManager $userManager, ImageUploader $imageUploader)
    {
        $this->form             = $form;
        $this->request          = $request;
        $this->userManager      = $userManager;
        $this->imageUploader    = $imageUploader;
    }

    /**
     * form submission verification
     * @return bool
     */
    public function process(string $type)
    {
        $this->currentAvatar = $this->form->getData()->getAvatar();
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

    protected function onSuccessNew()
    {
        $userFormData = $this->form->getData();
        $this->userManager->addNewUserToDb($userFormData);
    }

    protected function onSuccessEdit()
    {
        $userFormData = $this->form->getData();
        //dump($userFormData->getAvatar());
        /** @var File $image */
        if ($userFormData->getAvatar() === null) {
            $imageName = $this->currentAvatar;

            if($imageName === null) {
                $imageName = '';
            }

            $this->userManager->updateUserIntoDb($userFormData, $imageName);
        }
        else {
            $image = new File($userFormData->getAvatar());
            $imageName = $this->imageUploader->upload($image);
            $this->userManager->updateUserIntoDb($userFormData, $imageName);
        }
    }
}