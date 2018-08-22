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

        public function findAdvertsLinkedByUser($userId)
    {
            $conn = $this->getEntityManager()->getConnection();
            $sql = "SELECT w.user_id, a.title, a.description, a.price, a.creation_date, a.slug, 
                    c.label AS category, 
                    s.label AS section, 
                    u.pseudo AS pseudo,
                    p.url AS photo 
                    FROM wishlist w 
                    LEFT JOIN advert a ON a.id = w.advert_id 
                    LEFT JOIN category c ON a.category_id = c.id 
                    LEFT JOIN section s ON a.section_id = s.id 
                    LEFT JOIN user u ON a.user_id = u.id
                    LEFT JOIN photo p ON a.id = p.advert_id
                    WHERE w.user_id = :userId";
            $stmt = $conn->prepare($sql);
            $stmt->execute(['userId' => $userId]);

            return $stmt->fetchAll();
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
}

