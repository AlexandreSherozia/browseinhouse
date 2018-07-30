<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    protected $avatarDir;

    public function __construct(string $avatarDir)
    {
        $this->avatarDir = $avatarDir;
    }

    public function upload(UploadedFile $image)
    {
        $fileName = time(). '_' . rand(0, 100) . '.' . $image->guessExtension();

        $image->move($this->getAvatarDirectory(), $fileName);

        return $fileName;
    }

    public function getAvatarDirectory()
    {
        return $this->avatarDir;
    }
}