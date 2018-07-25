<?php

namespace App\User;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

class UserManager
{

    private $objectManager;

    public function __construct(ObjectManager $om)
    {
        $this->objectManager = $om;
    }

    public function addNewUser(User $user)
    {

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        return 'Congratulation ! You have been registered !';
    }

}