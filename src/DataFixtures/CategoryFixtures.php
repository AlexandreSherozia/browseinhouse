<?php

namespace App\DataFixtures;


use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class CategoryFixtures extends Fixture implements OrderedFixtureInterface
{

    private $categories = ['Real Estate', 'Vehicle', 'Mobiles', 'Accessories', 'Electronics', 'Music',
       'Fourniture', 'Jobs', 'Pets', 'Fashion', 'Miscellaneous', 'Informatics'];

    /**
     * Generate a table with all categories
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < count($this->categories); $i++)
        {
            $category = new Category();
            $category->setLabel($this->categories[$i]);

            $manager->persist($category);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}