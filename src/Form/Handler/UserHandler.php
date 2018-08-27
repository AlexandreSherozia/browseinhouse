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
        $currentAvatar,
        $mailer;

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
     * @param string $type purpose of the form
     * (registration or edition of a user profile)
     * @param Form $form
     * @param Request $request
     *
     * @return bool
     */
    public function process(string $type, Form $form, Request $request): bool
    {
        $this->form = $form;
        $this->request = $request;
        $this->currentAvatar = $this->form->getData()->getAvatar();
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {

            if ($type === 'new') {
                // the process is for a registration
                $token = $this->tokenMakerForAccountValidation();
                $this->onSuccessNew($token);
                $this->mailer->sendEmail($this->form, $token);

                return true;
            }
            if ($type === 'edit') {
                // the process is for edition
                $this->onSuccessEdit();

                return true;
            }
        }

        return false;
    }

    protected function onSuccessNew($token): void
    {
        $userFormData = $this->form->getData();
        $this->userManager->addNewUserToDb($userFormData, $token);
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

    public function tokenizerForAccountValidation()
    {
        return md5(uniqid('token', true));
    }
}
