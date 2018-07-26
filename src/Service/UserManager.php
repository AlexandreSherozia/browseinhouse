<?php

namespace App\Service;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $em;
    private $repository;
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
        $this->encoder = $encoder;
    }

    public function myPersist(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();
    }

}