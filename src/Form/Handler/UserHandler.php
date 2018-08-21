<?php

namespace App\Form\Handler;

use App\Service\ImageUploader;
use App\Service\Mailer;
use App\Service\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class UserHandler
{
    protected $form;
    protected $request;
    protected $userManager;
    protected $imageUploader;
    protected $currentAvatar;

    /**
     * UserHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param UserManager $userManager
     * @param ImageUploader $imageUploader
     */
    public function __construct(Form $form, Request $request, UserManager $userManager, ImageUploader $imageUploader)
    {
        $this->form             = $form;
        $this->request          = $request;
        $this->userManager      = $userManager;
        $this->imageUploader    = $imageUploader;
    }

    /**
     * @param string $type
     * @param Mailer $mailer
     * @return bool
     */
    public function process(string $type, Mailer $mailer = null)
    {
        $this->currentAvatar = $this->form->getData()->getAvatar();
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            if ($type === 'new') {
                $this->onSuccessNew();

                $mailer->sendEmail($this->form);

                return true;
            }
            if ($type === 'edit') {
                $this->onSuccessEdit();

                return true;
            }
        }

        return false;
    }

    protected function onSuccessNew(): void
    {
        $userFormData = $this->form->getData();
        $this->userManager->addNewUserToDb($userFormData);
    }

    protected function onSuccessEdit():void
    {
        $userFormData = $this->form->getData();
        //dump($userFormData->getAvatar());
        /** @var File $image */
        if ($userFormData->getAvatar() === null) {
            $imageName = $this->currentAvatar;

            if ($imageName === null) {
                $imageName = '';
            }

            $this->userManager->updateUserIntoDb($userFormData, $imageName);
        } else {
            $image = new File($userFormData->getAvatar());
            $imageName = $this->imageUploader->upload($image);
            $this->userManager->updateUserIntoDb($userFormData, $imageName);
        }
    }
}
