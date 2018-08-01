<?php

namespace App\Repository;

use App\Entity\Advert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Advert|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advert|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advert[]    findAll()
 * @method Advert[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvertRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Advert::class);
    }


    public function findAdvertsBySection($id)
    {
        return $this->createQueryBuilder('a')
            ->where('a.section = :val')
            ->setParameter('val', $id)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAdvertsByCategoryAndSection($sectionId, $categoryId)
    {
        return $this->createQueryBuilder('a')
            ->where('a.section = :val')
            ->setParameter('val', $sectionId)
            ->andWhere('a.category = :category')
            ->setParameter('category', $categoryId)
            ->orderBy('a.id','DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAdvertsByCategory($id)
    {
        return $this->createQueryBuilder('a')
            ->where('a.section = :val')
            ->setParameter('val', 1)
            ->andWhere('a.category = :category')
            ->setParameter('category', $id)
            ->orderBy('a.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function joinAdvertCategorySectionUser()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'c', 's', 'u')
            ->leftJoin('a.category', 'c')
            ->where('a.category = c.id')
            ->leftJoin('a.section', 's')
            ->where('a.section = s.id')
            ->leftJoin('a.user', 'u')
            ->where('a.user = u.id')
            ->orderBy('a.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

}
