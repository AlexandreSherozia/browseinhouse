<?php

namespace App\Form\Handler;


use App\Entity\Advert;
use App\Entity\User;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ContactHandler
{
    protected   $form,
                $contacterUser,
                $contactedUser,
                $advert,
                $request;

    /**
     * ContactHandler constructor.
     * @param $form
     * @param $contacterUser
     * @param $contactedUser
     * @param $advert
     * @param $request
     */
    public function __construct(Form $form, User $contacterUser, User $contactedUser, Advert $advert, Request $request)
    {
        $this->form = $form;
        $this->contacterUser = $contacterUser;
        $this->contactedUser = $contactedUser;
        $this->advert = $advert;
        $this->request = $request;
    }

    public function getForm()
    {
        return $this->form;
    }


}