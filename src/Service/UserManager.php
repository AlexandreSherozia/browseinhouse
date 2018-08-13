<?php

namespace App\Service;


use App\Entity\Advert;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    private $em;
    private $repository;
    private $encoder;

    /**
     * UserManager constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
        $this->encoder = $encoder;
    }

    /**
     * Encode password and insert new user in db
     * @param User $user
     */
    public function addNewUserToDb(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function updateUserIntoDb(User $user, string $imageName)
    {
        $user->setAvatar($imageName);
        $this->em->persist($user);
        $this->em->flush();
    }

    public function removeAvatar(int $user_id)
    {
        $user = $this->em->getRepository(User::class)->find($user_id);

        $user->setAvatar(null);
        $this->em->flush();
    }

    public function getUserList()
    {
        $userList = $this->em->getRepository(User::class)->findAll();

        return $userList;
    }

    public function removeUser($user_id)
    {
        $user = $this->em->getRepository(User::class)->find($user_id);
        $adverts = $this->em->getRepository(Advert::class)->findBy(['user'=>$user_id]);

        foreach($adverts as $advert) {
            $this->em->remove($advert);
        }

        $this->em->remove($user);

        $this->em->flush();
    }

}