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


    public function myPersist(Advert $advert)
    {

        $advert->setUser($this->connected_User);

        $this->em->persist($advert);
        $this->em->flush();

        return $advert;
}

    /**
     * Returns all categories of the section "Buying"
     * called by showBuyingCategories()
     */
    public function getAdvertsBySection($label)
    {
        return $this->advertRepository->findAdvertsBySection($label);
    }


    public function getAdvertsByCategoryAndSection($sectionlabel, $categorylabel)
    {
        return $this->advertRepository->findAdvertsByCategoryAndSection($sectionlabel, $categorylabel);
    }

    public function getAdvertsByCategory($id)
    {
        return $this->advertRepository->findAdvertsByCategory($id);
    }

    public function findAdvert($advertslug)
    {
        return $this->advertRepository->findOneBy(['slug' => $advertslug]);
    }


    public function removeAdvert($advertid)
    {
        $advert = $this->advertRepository->find($advertid);
        $this->em->remove($advert);
        $this->em->flush();
    }

    public function getAdvertRepo()
    {
        return $this->advertRepository;
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