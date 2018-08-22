<?php

namespace App\Service;


use App\Entity\Wishlist;
use Doctrine\ORM\EntityManagerInterface;

class WishlistManager
{
    private $em;

    /**
     * WishlistManager constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * insert a new link in wishlist table between an advert and an user
     *
     * @param int $advertId
     * @param int $userId
     */
    public function createNewWishlistRow(int $advertId, int $userId)
    {
        $control = $this->em->getRepository(Wishlist::class)
            ->findLinkBetweenAdvertAndUser($advertId, $userId);

        if(intval($control) === 0)
        {
            $wishlist = new Wishlist();
            $wishlist->setAdvertId($advertId);
            $wishlist->setUserId($userId);

            $this->em->persist($wishlist);
            $this->em->flush();
        }
    }

    /**
     * retrieve adverts from an user's wishlist
     *
     * @param int $userId
     *
     * @return array
     */
    public function getAdvertsInWishlist(int $userId)
    {
        return $this->em->getRepository(Wishlist::class)
            ->findAdvertsLinkedByUser($userId);
    }
}
