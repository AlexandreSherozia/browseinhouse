<?php

namespace App\Service;


use App\Entity\Wishlist;
use Doctrine\ORM\EntityManagerInterface;

class WishlistManager
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em               = $em;
    }

    public function createNewWishlistRow($advertId, $userId)
    {
        $control = $this->em->getRepository(Wishlist::class)->findLinkBetweenAdvertAndUser($advertId, $userId);

        if(intval($control) === 0)
        {
            $wishlist = new Wishlist();
            $wishlist->setAdvertId($advertId);
            $wishlist->setUserId($userId);

            $this->em->persist($wishlist);
            $this->em->flush();
        }
    }

    public function getAdvertsInWishlist($userId)
    {
        return $this->em->getRepository(Wishlist::class)->findAdvertsLinkedByUser($userId);
    }
}