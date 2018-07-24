<?php

namespace App\User;


use Doctrine\ORM\EntityManager;

class UserManager
{

    public function addNewUser(EntityManager $entityManager, UserType $userType)
    {
        #recupérer les infos du formulaire
        #créer un objet User
        #remplir le User avec les infos

        # persist
        # flush
    }

}