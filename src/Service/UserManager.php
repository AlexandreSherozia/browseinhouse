<?php

namespace App\Service;

use App\Entity\Advert;
use App\Entity\Subscription;
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
     *
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em,
                                UserPasswordEncoderInterface $encoder
    )
    {
        $this->em = $em;
        $this->repository = $em->getRepository(User::class);
        $this->encoder = $encoder;
    }

    /**
     * Encode password and insert new user in db
     *
     * @param User $user
     *
     * @return User
     */
    public function addNewUserToDb(User $user): User
    {
        $user->setPassword(
            $this->encoder->encodePassword($user, $user->getPassword())
        );
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function updateUserIntoDb(User $user, string $imageName): void
    {
        $user->setAvatar($imageName);
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Set user avatar url to null in db
     *
     * @param int $user_id
     */
    public function removeAvatar(int $user_id): void
    {
        $user = $this->repository->find($user_id);

        $user->setAvatar(null);
        $this->em->flush();
    }

    public function getUserList(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param User $follower
     *
     * @return array User[] users followed by this follower
     */
    public function getSubscriptionList(User $follower): array
    {
        return $this->em->getRepository(Subscription::class)
            ->findBy(['follower' => $follower]);
    }

    public function removeUser(int $user_id): void
    {
        $user = $this->repository->find($user_id);
        $adverts = $this->em->getRepository(Advert::class)->findBy(['user' => $user_id]);

        foreach ($adverts as $advert) {
            $this->em->remove($advert);
        }

        $this->em->remove($user);
        $this->em->flush();
    }
}