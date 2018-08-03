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

    public function load(ObjectManager $manager)
    {
        $categoryRepository = $manager->getRepository(Category::class);
        $sectionRepository  = $manager->getRepository(Section::class);
        $userRepository     = $manager->getRepository(User::class);

        $totalNumberOfCategories = $categoryRepository->getTotalNumberOfCategories();
        $totalNumberOfSections   = $sectionRepository->getTotalNumberOfSections();

        for ($i = 0; $i < $this->numberOfIterations; $i++)
            {
                $advert = new Advert();

                $numberSectionRandom    = mt_rand(1, $totalNumberOfSections);
                $numberCategoryRandom   = mt_rand(1, $totalNumberOfCategories);

                $category = $categoryRepository->find($numberCategoryRandom);
                $section  = $sectionRepository->find($numberSectionRandom);
                $user     = $userRepository->findAll();
                $key = array_rand($user);
                $user = $userRepository->find($user[$key]);

                $advert->setTitle('product '.$i);
                $advert->setDescription($i. 'Vente 2Gîtes 3 Clés tarif 2 nuits:200€, 50€ par nuit supplémentaire Semaine :380€,460€,560€ selon période.
2 Maisons 70 m², au calme, située au coeur de la baie de Somme, entre le parc du Marquenterre et le Crotoy ouvert toute l\'année. Toutes charges comprises, taxe de séjour incluse,dégressif selon durée Animaux acceptés,parking privé,wifi.');
                $advert->setPrice(mt_rand(10, 1000));
                $advert->setSlug('product-slug'.$i);

                $advert->setCategory($category);
                $advert->setSection($section);
                /** @var User $user */
                $advert->setUser($user);

                $manager->persist($advert);
            }
            $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}