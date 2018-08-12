<?php

namespace App\DataFixtures;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AdvertFixtures extends Fixture implements OrderedFixtureInterface
{

    private $numberOfIterations = 100;
    private $categoryRepository;
    private $sectionRepository;
    private $userRepository;
    private $om;
    private $photoUrl = 'advert_placeholder.jpg';

    public function __construct(ObjectManager $manager)
    {
        $this->om = $manager;
        $this->categoryRepository = $manager->getRepository(Category::class);
        $this->sectionRepository  = $manager->getRepository(Section::class);
        $this->userRepository     = $manager->getRepository(User::class);
    }

    /**
     * pick a random Id in db from a table linked to a Doctrine entity
     * @param string $entity the entity required
     * @return int an Id of the entity
     */
    public function getRandomEntityId(string $entity): int
    {
        switch($entity) {
            case 'Category';
            $repository = $this->categoryRepository;
            break;

            case 'Section';
            $repository = $this->sectionRepository;
            break;

            case 'User';
            $repository = $this->userRepository;
            break;
        }

        $entity = $repository->findAll();
        $entityAllIds = [];
        foreach($entity as $val) {
            $entityAllIds[] = $val->getId();
        }
        $entityRandomId = $entityAllIds[array_rand($entityAllIds)];

        return $entityRandomId;
    }

    /**
     * builds an adverts table with random values from previously created fixtures : sections, categories and users
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < $this->numberOfIterations; $i++)
            {
                $date = new \DateTime('2018-06-04 00:00:01');
                $date = $date->modify('+'.$i.'minute');
                $advert = new Advert();

                $category = $this->categoryRepository->find($this->getRandomEntityId('Category'));
                $section = $this->sectionRepository->find($this->getRandomEntityId('Section'));
                $user = $this->userRepository->find($this->getRandomEntityId('User'));

                $advert->setTitle('product '.$i);
                $advert->setDescription('Description '. $i. ': Lorem ipsum dolor sit amet, consectetur 
                adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim 
                veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute 
                irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
                sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
                $advert->setPrice(mt_rand(10, 1000));
                $advert->setSlug('product-slug'.$i);

                $advert->setCategory($category);
                $advert->setSection($section);
                $advert->setUser($user);

                $manager->persist($advert);

                $advert->setCreationDate($date);

                $manager->persist($advert);
            }
            $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}