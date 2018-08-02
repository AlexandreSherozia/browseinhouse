<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 02/08/2018
 * Time: 12:28
 */

namespace App\DataFixtures;


use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Section;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AdvertFixtures extends Fixture
{

    private $numberOfIterations = 10;

    public function load(ObjectManager $manager)
    {
        $totalNumberOfCategories = $manager->getRepository(Category::class)->getTotalNumberOfCategories();
        $totalNumberOfsections   = $manager->getRepository(Section::class)->getTotalNumberOfSections();
        $totalNumberOfUsers      = $manager->getRepository(Section::class)->getTotalNumberOfSections();




        for ($i = 0; $i < $this->numberOfIterations; $i++)
            {
                $advert = new Advert();

                $numberCategoryRandom   = mt_rand(1, $totalNumberOfCategories);
                $numbersectionRandom    = mt_rand(1, $totalNumberOfsections);
                $numberUserRandom       = mt_rand(1, $totalNumberOfUsers);

                $category = $manager->getRepository(Category::class)->find($numberCategoryRandom);
                $section  = $manager->getRepository(Section::class)->find($numbersectionRandom);
                $user     = $manager->getRepository(User::class)->find($numberUserRandom);


                $advert->setTitle('product '.$i);
                $advert->setDescription($i. 'Vente 2Gîtes 3 Clés tarif 2 nuits:200€, 50€ par nuit supplémentaire Semaine :380€,460€,560€ selon période.
2 Maisons 70 m², au calme, située au coeur de la baie de Somme, entre le parc du Marquenterre et le Crotoy ouvert toute l\'année. Toutes charges comprises, taxe de séjour incluse,dégressif selon durée Animaux acceptés,parking privé,wifi.');
                $advert->setPrice(mt_rand(10, 1000));
                $advert->setSlug('product-slug'.$i);

                $advert->setCategory($category);
                $advert->setSection($section);

                $advert->setUser($user);

                $manager->persist($advert);
            }
            $manager->flush();
    }
}