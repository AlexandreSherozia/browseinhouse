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
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AdvertFixtures extends Fixture implements OrderedFixtureInterface
{

    private $numberOfIterations = 100;

    public function load(ObjectManager $manager)
    {
        $categoryRepository = $manager->getRepository(Category::class)->getTotalNumberOfCategories();
        $sectionRepository  = $manager->getRepository(Section::class)->getTotalNumberOfSections();
        $userRepository     = $manager->getRepository(User::class)->getTotalNumberOfUsers();


        for ($i = 0; $i < $this->numberOfIterations; $i++)
            {
                $advert = new Advert();

                $numberCategoryRandom   = mt_rand(1, $categoryRepository);
                $numberSectionRandom    = mt_rand(1, $sectionRepository);
                $numberUserRandom       = mt_rand(1, $userRepository);


                $category = $manager->getRepository(Category::class)->find($numberCategoryRandom);
                $section  = $manager->getRepository(Section::class)->find($numberSectionRandom);
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
    public function getOrder()
    {
        // TODO: Implement getOrder() method.
    }
}