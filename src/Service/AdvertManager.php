<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 25/07/2018
 * Time: 07:28
 */

namespace App\Service;


use App\Entity\Advert;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AdvertManager
{
    private $em, $repository;

    /**
     * AdvertManager constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em           = $em;
        $this->repository   = $em->getRepository(Advert::class);

    }

    /**
     * Returns all categories of the section "Buying"
     * called by showBuyingCategories()
     */
    public function getBuyingCategories()
    {
        $this->repository->getBuyingCategories();
    }


    /**
     * @param $user
     * @return mixed
     * Returns  all adverts of user
     */
    /*public function getAdvertByUser($user)
    {
        return $this->repository->getAll($user);
    }*/

    /*public function getAll($user)*/


    public function myPersist(Advert $advert/*, User $user*/, $slug, $categoryId)
    {
        /*$advert->setUser($user);*/
        $categories =

        $advert->setSlug($slug);
        $advert->setCategory($categoryId);

       /* $advert->setCategory($advert->getCategory()->getId(1));*/

        $this->em->persist($advert);
        $this->em->flush();

        return $advert;
    }


}