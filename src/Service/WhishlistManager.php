<?php

namespace App\Service;


use App\Entity\Whishlist;
use Doctrine\ORM\EntityManagerInterface;

class WhishlistManager
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em               = $em;
    }

    public function createNewWhishlistRow($advertId, $userId)
    {
        $control = $this->em->getRepository(Whishlist::class)->findLinkBetweenAdvertAndUser($advertId, $userId);

        if(intval($control) === 0)
        {
            $whishlist = new Whishlist();
            $whishlist->setAdvertId($advertId);
            $whishlist->setUserId($userId);

            $this->em->persist($whishlist);
            $this->em->flush();
        }
    }

    public function getAdvertsInWhislist($userId)
    {
        return $this->em->getRepository(Whishlist::class)->findAdvertsLinkedByUser($userId);
    }
}