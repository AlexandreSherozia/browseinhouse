<?php

namespace App\Service;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Photo;
use App\Entity\Section;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAware;
use Symfony\Component\Security\Core\Security;

class AdvertManager extends PaginatorAware
{
    private $em, $advertRepository, $connected_User;

    /**
     * AdvertManager constructor.
     *
     * @param EntityManagerInterface $em
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em               = $em;
        $this->advertRepository = $em->getRepository(Advert::class);
        $this->connected_User   = $security->getUser();
    }

//
//    public function getEm()
//    {
//        return $this->em;
//    }

    /**
     * add an advert created with no photo linked
     *
     * @param Advert $advert
     *
     * @return Advert
     */
    public function create(Advert $advert): Advert
    {
        $advert->setUser($this->connected_User);
        $this->em->persist($advert);
        $this->em->flush();
        return $advert;
    }

    /**
     * Add an advert created with linked photos in db
     *
     * @param Advert $advert
     * @param Photo $photo
     * @return Advert
     */
    public function createWithPhoto(Advert $advert, Photo $photo): Advert
    {
        $advert->setUser($this->connected_User);
        $this->em->persist($advert);
        $this->em->persist($photo);
        $this->em->flush();
        return $advert;
    }

    /**
     * Returns all categories of any section
     *
     * @param $label
     *
     * @return mixed
     */
    public function getAdvertsBySection(string $label):array
    {
        return $this->advertRepository->findAdvertsBySection($label);
    }

    /**
     * @param string $sectionlabel
     * @param string $categorylabel
     *
     * @return mixed
     */
    public function getAdvertsByCategoryAndSection(string $sectionlabel, string $categorylabel):array
    {
        return $this->advertRepository->findAdvertsByCategoryAndSection($sectionlabel, $categorylabel);
    }

    /**
     * @param string $categorylabel
     *
     * @return mixed
     */
    public function getAdvertsByCategory(string $categorylabel):array
    {
        return $this->advertRepository->findAdvertsByCategory($categorylabel);
    }

    /**
     * Find an advert by its slug
     *
     * @param string $advertslug
     * @return Advert|null|object
     */
    public function findAdvert(string $advertslug)
    {
        return $this->advertRepository->findOneBy(['slug' => $advertslug]);
    }

    public function getAllSections():array
    {
        return $this->em->getRepository(Section::class)->findAll();
    }

    public function getAllCategories():array
    {
        return $this->em->getRepository(Category::class)->findAll();
    }

    public function getAllAdvertsInfos():array
    {
        return $this->advertRepository->joinAdvertCategorySectionUser();
    }

    public function removeAdvert(int $advertid)
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

    /**
     * get all adverts created by an user
     *
     * @param int $userId
     *
     * @return mixed
     */
    public function getAdvertsByUser(int $userId):array
    {
        return $this->advertRepository->findAdvertsByUser($userId);
    }

    /**
     * get all five most recent adverts created for display in index
     *
     * @return mixed
     */
    public function getFiveLastAdverts():array
    {
        return $this->advertRepository->findFiveLastAdverts();
    }

    public function getCategoriesInSections():array
    {
        return $this->advertRepository->findAdvertsCategoriesInSections();
    }
}
