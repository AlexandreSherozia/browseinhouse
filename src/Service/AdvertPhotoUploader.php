<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\File;

class AdvertPhotoUploader
{
    protected $photoDirectory;

    /**
     * AdvertPhotoUploader constructor.
     * @param $photo
     */
    public function __construct(string $photoDirectory)
    {
        $this->photoDirectory = $photoDirectory;
    }

    public function uploadPhoto(File $photo)
    {


        //$fileName = time(). '_' . mt_rand(0,100) . '.' .$photo->guessExtension();
        $fileName = $this->generateUniqueFileName() . '.' .$photo->guessExtension();

        $photo->move($this->getPhotoDirectory(), $fileName);

        return $fileName;
    }

    private function getPhotoDirectory()
    {
        return $this->photoDirectory;
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid('photo', true));
    }


}