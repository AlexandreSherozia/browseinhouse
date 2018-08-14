<?php

namespace App\Repository;


use App\Entity\Wishlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class WishlistRepository  extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Wishlist::class);
    }

    public function findLinkBetweenAdvertAndUser($advertId, $userId)
    {
        return $this->createQueryBuilder('w')
            ->select('COUNT(w.id)')
            ->where('w.userId = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('w.advertId = :advertId')
            ->setParameter('advertId', $advertId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findAdvertsLinkedByUser($userId)
    {
        return $this->createQueryBuilder('a')
            ->select('a','u', 'c', 's', 'w')
            ->join('a.category', 'c')
            ->join('a.section', 's')
            ->join('a.user', 'u')
            ->where('w.userId = '. $userId)
            ->andWhere('w.advertId = a.advertId')
            ->getQuery()
            ->getResult();
    }
}

