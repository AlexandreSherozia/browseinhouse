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
            ->select('a', 's')
            ->join('a.section', 's')
            ->where('s.label = :val')
            ->setParameter('val', $label)
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAdvertsByCategoryAndSection($sectionlabel, $categorylabel)
    {
        return $this->createQueryBuilder('a')
            ->select('a','s','c')
            ->leftJoin('a.section', 's')
            ->leftJoin('a.category', 'c')
            ->where('s.label = :toto')
            ->andWhere('c.label = :val2')
            ->setParameter('toto', $sectionlabel)
            ->setParameter('val2', $categorylabel)
            ->orderBy('a.creationDate', 'DESC')
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

    public function joinAdvertCategorySectionUser()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'c', 's', 'u')
            ->leftJoin('a.category', 'c')
            ->leftJoin('a.section', 's')
            ->leftJoin('a.user', 'u')
            ->orderBy('a.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAdvertsByUser($id)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.category', 'c')
            ->leftJoin('a.section', 's')
            ->where('a.user = :id')
            ->setParameter('id', $id)
            ->orderBy('a.creationDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findFiveLastAdverts()
    {
        return $this->createQueryBuilder('a')
            ->select('a', 'c', 's', 'u')
            ->leftJoin('a.category', 'c')
            ->leftJoin('a.section', 's')
            ->leftJoin('a.user', 'u')
            ->orderBy('a.creationDate', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

}
