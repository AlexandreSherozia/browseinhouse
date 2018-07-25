<?php

namespace App\User;


use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $objectManager;
    private $passwordEncoder;

    public function __construct(ObjectManager $objectManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->objectManager = $objectManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function addNewUser(User $user)
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

        $this->objectManager->persist($user);
        $this->objectManager->flush();

        return 'Congratulation, you have been registered !';
    }

}