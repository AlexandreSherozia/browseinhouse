<?php

namespace App\DataFixtures;


use App\Entity\Advert;
use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class PhotoFixtures extends Fixture implements OrderedFixtureInterface
{
    private $advertRepository;
    private $om;
    private $photoUrl = 'advert_placeholder.jpeg';

    public function __construct(ObjectManager $manager)
    {
        $this->om = $manager;
        $this->advertRepository = $manager->getRepository(Advert::class);
    }

    /**
     * Set a default photo for each advert in db
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $adverts = $this->advertRepository->findAll();

        foreach ($adverts as $advert)
        {
            $photo = new Photo();
            $photo->setAdvert($advert);
            $categoryName = str_replace(' ', '_', strtolower($advert->getCategory()->getLabel()));
            $photo->setUrl('fixture_' . $categoryName .'.jpg');
            $photo->setName('default_' . $categoryName);

            $manager->persist($photo);
        }
        $manager->flush();
    }

    public function getOrder()
    {
        return 5;
    }
}