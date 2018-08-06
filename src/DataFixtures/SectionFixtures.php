<?php

namespace App\DataFixtures;


use App\Entity\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class SectionFixtures extends Fixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $section1 = new Section();
        $section1->setLabel('Shopping');
        $manager->persist($section1);

        $section2 = new Section();
        $section2->setLabel('Jobs');
        $manager->persist($section2);

        $section3 = new Section();
        $section3->setLabel('Rent');
        $manager->persist($section3);

        $section4 = new Section();
        $section4->setLabel('Services');
        $manager->persist($section4);

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}