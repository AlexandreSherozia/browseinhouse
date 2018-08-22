<?php

namespace App\Repository;

use App\Entity\Subscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subscription[]    findAll()
 * @method Subscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subscription::class);
    }

    public function ifFollowingExists($follower, $followed)
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->where('s.follower = :val')
            ->setParameter('val', $follower)
            ->andWhere('s.subscribed = :val2')
            ->setParameter('val2', $followed)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function followingRelation($follower, $followed)
    {
        return $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.follower = :val')
            ->setParameter('val', $follower)
            ->andWhere('s.subscribed = :val2')
            ->setParameter('val2', $followed)
            ->getQuery()
            ->getSingleResult();
    }

    public function subscriptionStatus($follower, $star)
    {
        return $this->createQueryBuilder('s')
            ->select('s','u')
            ->where('s.follower = :val')
            ->setParameter('val', $follower)
            ->leftJoin('s.subscribed', 'u')
            ->andWhere('u.pseudo = :val2')
            ->setParameter('val2', $star)
            ->getQuery()
            ->getResult();
    }
}
