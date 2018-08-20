<?php

namespace App\Tests\Entity;


use App\Entity\Advert;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{

    public function testCategoryCanSetAndGetAnId()
    {
        $category = new Category();
        $category->setId(2);

        $this->assertEquals(2, $category->getId());
    }

    public function testCategoryCanSetAndGetALabel()
    {
        $category = new Category();
        $category->setLabel('LabelOfCategory');

        $this->assertEquals('LabelOfCategory', $category->getLabel());
    }

    public function testCategoryCanBeAddedToAndGetAnAdvertCollection()
    {
        $category = new Category();
        $advert = new Advert();

        $category->addAdvert($advert);
        $this->assertSame($category, $category->addAdvert($advert));
        $this->assertContainsOnlyInstancesOf(Advert::class, $category->getAdverts());
    }

}