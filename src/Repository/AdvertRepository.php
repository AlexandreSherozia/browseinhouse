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


    public function findAdvertsBySection($label)
    {
        return $this->createQueryBuilder('a')
            ->join('a.section', 's')
            ->addSelect('s')
            ->andWhere('s.label = :val')
            ->setParameter('val', $label)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAdvertsByCategoryAndSection($sectionlabel, $categorylabel)
    {
        return $this->createQueryBuilder('a')
            ->join('a.section', 's')
            ->addSelect('s')
            ->andWhere('s.label = :val')
            ->setParameter('val', $sectionlabel)
            ->join('a.category', 'c')
            ->addSelect('c')
            ->andWhere('c.label = :val')
            ->setParameter('val', $categorylabel)
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

    public function findAdvertBySlug($advertSlug)
    {
        return $this->createQueryBuilder('a')
            ->where('a.slug = :val')
            ->setParameter('val', $advertSlug)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Advert[] Returns an array of Advert objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Advert
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
