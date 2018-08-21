<?php

namespace App\Form\Handler;

use App\Service\AvatarUploader;
use App\Service\Mailer;
use App\Service\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class UserHandler
{
    protected $form,
        $request,
        $userManager,
        $imageUploader,
        $currentAvatar;

    /**
     * UserHandler constructor.
     *
     * @param UserManager $userManager
     * @param AvatarUploader $avatarUploader
     * @param Mailer $mailer
     */
    public function __construct(UserManager $userManager,
                                AvatarUploader $avatarUploader, Mailer $mailer)
    {
        $this->userManager = $userManager;
        $this->imageUploader = $avatarUploader;
        $this->mailer = $mailer;
    }

    /**
     * Processing the user form
     *
     * @param string $type purpose of the form (registration or edition of a user profile)
     * @param Mailer|null $mailer
     *
     * @return bool
     */
    public function process(string $type, Form $form, Request $request)
    {
        $this->form = $form;
        $this->request = $request;
        $this->currentAvatar = $this->form->getData()->getAvatar();
        $this->form->handleRequest($this->request);

        if ($this->form->isSubmitted() && $this->form->isValid()) {

            if ($type === 'new') {
                $this->onSuccessNew();
                $this->mailer->sendEmail($this->form);

                return true;
            }
            if ($type === 'edit') {
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

    /**
     * @var File $avatar the avatar image of the user
     */
    protected function onSuccessEdit()
    {
        $userFormData = $this->form->getData();

        if ($userFormData->getAvatar() === null) {
            $avatarName = $this->currentAvatar;

            if ($avatarName === null) {
                $avatarName = '';
            }

            $this->userManager->updateUserIntoDb($userFormData, $avatarName);
        } else {
            $avatar = new File($userFormData->getAvatar());
            $avatarName = $this->imageUploader->upload($avatar);
            $this->userManager->updateUserIntoDb($userFormData, $avatarName);
        }
    }
}
