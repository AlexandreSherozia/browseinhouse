<?php

namespace App\Service;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Section;
use App\Entity\User;
use App\Repository\SectionRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class AdvertManager
{
    private $em, $advertRepository, $connected_User;

    /**
     * AdvertManager constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em               = $em;
        $this->advertRepository = $em->getRepository(Advert::class);
        $this->connected_User   = $security->getUser();
    }


    public function getEm()
    {
        return $this->em;
    }

    public function myPersist(Advert $advert, $slug)
    {

        $advert->setUser($this->connected_User);

        $advert->setSlug($slug);

        //$advert->setCategory($categoryId); se récupère automatiquement par getData()

        $this->em->persist($advert);
        $this->em->flush();

        return $advert;
    }

    /**
     * Returns all categories of the section "Buying"
     * called by showBuyingCategories()
     */
    public function getAdvertsBysection($id)
    {
        return $this->advertRepository->findAdvertsBySection($id);
    }


    public function getAdvertsByCategoryAndsection($sectionId, $categoryId)
    {
        return $this->advertRepository->findAdvertsByCategoryAndSection($sectionId, $categoryId);
    }

    public function getAdvertsByCategory($id)
    {
        return $this->advertRepository->findAdvertsByCategory($id);
    }

    public function showAdvert($id)
    {
        return $this->advertRepository->find($id);
    }

    public function getAllSections()
    {
        return $this->em->getRepository(Section::class)->findAll();
    }

    public function getAllCategories()
    {
        return $this->em->getRepository(Category::class)->findAll();
    }

    public function getAllAdvertsInfos()
    {
        return $this->advertRepository->joinAdvertCategorySectionUser();
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


}