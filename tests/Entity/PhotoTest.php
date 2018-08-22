<?php

namespace App\Tests\Entity;


use App\Entity\Advert;
use App\Entity\Photo;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;

class PhotoTest extends TestCase
{
    public function testPhotoCanSetAndGetImageFile()
    {
        $photo = new Photo();
        $image = $this->createMock(File::class);
        $photo->setImageFile($image);

        $this->assertInstanceOf(File::class, $photo->getImageFile());
    }

    public function testPhotoCanSetAndGetAName()
    {
        $photo = new Photo();
        $photo->setName('an-image-name');

        $this->assertEquals('an-image-name', $photo->getName());
    }

    public function testPhotoCanSetAndGetAnAdvertEntity()
    {
        $photo = new Photo();
        $advert = new Advert();

        $photo->setAdvert($advert);
        $this->assertInstanceOf(Advert::class, $photo->getAdvert());
    }

    public function testPhotoCanSetAndGetAnUrl()
    {
        $photo = new Photo();
        $photo->setUrl('image.jpg');

        $this->assertEquals('image.jpg', $photo->getUrl());
    }

}