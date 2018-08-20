<?php

use App\Entity\Advert;
use App\Entity\Photo;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class AdvertTest extends TestCase
{
    public function testAdvertCanBeCreated()
    {
        $advert = new Advert();
        $this->assertInstanceOf(Advert::class, $advert);
    }

    public function testAdvertHasACreationDateByDefaultAndCanGetIt()
    {
        $advert = new Advert();
        $date = new \DateTime;

        $this->assertEquals($date, $advert->getCreationDate());
    }

    public function testAdvertCanSetAndGetATitle()
    {
        $advert = new Advert();
        $advert->setTitle('Un Titre');

        $this->assertEquals('Un Titre', $advert->getTitle());
    }

    public function testAdvertCanSetAndGetADescription()
    {
        $advert = new Advert();
        $advert->setDescription('Une description utile');

        $this->assertEquals('Une description utile', $advert->getDescription());
    }

    public function testAdvertCanSetAndGetAPrice()
    {
        $advert = new Advert();
        $advert->setPrice(52.5);

        $this->assertEquals(52.5, $advert->getPrice());
    }

    public function testAdvertCanSetAndGetASlug()
    {
        $advert = new Advert();
        $advert->setSlug('this-is-a-slug');

        $this->assertEquals('this-is-a-slug', $advert->getSlug());
    }

    public function testAdvertCanSetAndGetACategory()
    {
        $advert = new Advert();
        $advert->setCategory(5);

        $this->assertEquals(5, $advert->getCategory());
    }

    public function testAdvertCanSetAndGetASection()
    {
        $advert = new Advert();
        $advert->setSection(2);

        $this->assertEquals(2, $advert->getSection());
    }

    public function testAdvertCanSetAndGetAnUserEntity()
    {
        $advert = new Advert();
        $user = new User();

        $advert->setUser($user);

        $this->assertEquals($user, $advert->getUser());
    }

    public function testAdvertCanAddAndGetPhotoEntity()
    {
        $advert = new Advert();
        $photo = $this->createMock(Photo::class);

        $this->assertSame($advert, $advert->addPhoto($photo));
        $this->assertContainsOnlyInstancesOf(Photo::class, $advert->getPhotos());
    }

}