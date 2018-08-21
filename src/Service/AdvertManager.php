<?php

namespace App\Service;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAware;
use Symfony\Component\Security\Core\Security;

class AdvertManager extends PaginatorAware
{
    private $em, $advertRepository, $connected_User, $whishlist;

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

    public function myPersist(Advert $advert)
    {
        $advert->setUser($this->connected_User);
        $this->em->persist($advert);
        $this->em->flush();
        return $advert;
    }

    public function myPersistWithPhoto(Advert $advert, $photo)
    {

        $advert->setUser($this->connected_User);
        $this->em->persist($advert);
        $this->em->persist($photo);
        $this->em->flush();
        return $advert;
    }



    /**
     * Returns all categories of any section"
     */
    public function getAdvertsBySection($label)
    {
        return $this->advertRepository->findAdvertsBySection($label);
    }


    public function getAdvertsByCategoryAndSection($sectionlabel, $categorylabel)
    {
        return $this->advertRepository->findAdvertsByCategoryAndSection($sectionlabel, $categorylabel);
    }

    public function getAdvertsByCategory($categorylabel)
    {
        return $this->advertRepository->findAdvertsByCategory($categorylabel);
    }

    public function findAdvert($advertslug)
    {
        return $this->advertRepository->findOneBy(['slug' => $advertslug]);
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

    public function removeAdvert($advertid)
    {
        $advert = $this->advertRepository->find($advertid);
        $this->em->remove($advert);
        $this->em->flush();
    }

    /**
     * @return \App\Repository\AdvertRepository|\Doctrine\Common\Persistence\ObjectRepository
     */
    public function getAdvertRepo()
    {
        return $this->advertRepository;
    }

    public function getAdvertsByUser($id)
    {
        return $this->advertRepository->findAdvertsByUser($id);
    }

    public function getFiveLastAdverts()
    {
        return $this->advertRepository->findFiveLastAdverts();
    }

    public function getCategoriesInSections()
    {
        return $this->advertRepository->findAdvertsCategoriesInSections();
    }

//    public function getAdvertsNumberInCategoryAndSection($sectionLabel, $categorylabel)
//    {
//        return intval($this->advertRepository->findAdvertsNumberinCategoryAndSection($sectionLabel, $categorylabel));
//    }

}