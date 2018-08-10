<?php

namespace App\Service;



use Symfony\Component\HttpFoundation\File\File;

class ImageUploader
{
    protected $avatarDir;

    public function __construct(string $avatarDir)
    {
        $this->avatarDir = $avatarDir;
    }

    /**
     * @param File $image
     * @return string
     * @throws \Gumlet\ImageResizeException
     */
    public function upload(File $image)
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