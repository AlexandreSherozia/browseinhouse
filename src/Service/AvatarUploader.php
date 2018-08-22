<?php

namespace App\Service;


use Symfony\Component\HttpFoundation\File\File;

class AvatarUploader
{
    protected $avatarDir;

    /**
     * AvatarUploader constructor
     *
     * @param string $avatarDir the directory where avatars pictures will be stored
     */
    public function __construct(string $avatarDir)
    {
        $this->avatarDir = $avatarDir;
    }

    /**
     * @param File $avatar the avatar picture of a user
     *
     * @return string
     */
    public function upload(File $avatar):string
    {
        $fileName = time(). '_' . rand(0, 100) . '.' . $avatar->guessExtension();

        $avatar->move($this->getAvatarDirectory(), $fileName);

        return $fileName;
    }

    public function getAvatarDirectory()
    {
        return $this->avatarDir;
    }
}
