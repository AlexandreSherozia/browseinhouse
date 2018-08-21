<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

/**
 * Class AdvertPhotoUploader
 * @package App\Service
 */
class AdvertPhotoUploader
{
    protected $flashBag;
    protected $mimeTypes;
    protected $validator;
    protected $photoDirectory;

    /**
     * AdvertPhotoUploader constructor.
     *
     * @param string $photoDirectory
     * @param FlashBagInterface $flashBag
     */
    public function __construct(string $photoDirectory, FlashBagInterface $flashBag)
    {
        $this->mimeTypes = ['jpeg', 'png'];
        $this->photoDirectory = $photoDirectory;
        $this->flashBag = $flashBag;
    }

    /**
     * @param File $photo
     * @return null|string
     */
    public function uploadPhoto(File $photo): ?string
    {
        if ($this->filterFileSize($photo) && $this->filterMimesTypes($photo)) {
            $fileName = $this->generateUniqueFileName() . '.' . $photo->guessExtension();
            $photo->move($this->getPhotoDirectory(), $fileName);

            return $fileName;
        }
        return false;
    }

    /**
     * @param $photo
     * @return bool
     * filters whether mimetypes are good and size of each file
     */
    private function filterMimesTypes($photo): bool
    {
        dump($photo->guessExtension());
        if (\in_array($photo->guessExtension(), $this->mimeTypes, true) &&
            is_file($photo)) {

            return true;
        }

        $this->flashBag->set('error', 'Only jpeg. jpg. and png. files accepted');

        return false;
    }


    /**
     * @param $photo
     * @return bool
     * Filter greater than 1Mo files
     */
    public function filterFileSize($photo): bool
    {
        if ($photo->getSize() < 1000000 &&
            is_file($photo)) {

            return true;
        }

        $this->flashBag->set('error', 'You can only upload 1MB per file');

        return false;

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
