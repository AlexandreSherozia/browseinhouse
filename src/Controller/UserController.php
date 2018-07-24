<?php

namespace App\Controller;


use App\User\UserManager;
use App\User\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{

    /**
     * @Route()
     * @param UserManager $userManager
     * @param Request $request
     */
    public function userRegistration(UserManager $userManager, Request $request)
    {
        $form = $this->createForm(UserType::class);


    }



}