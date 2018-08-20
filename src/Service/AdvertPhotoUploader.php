<?php

namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
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
     * @param string $photoDirectory
     * @param FlashBagInterface $flashBag
     * @param ValidatorInterface $validator
     */
    public function __construct(string $photoDirectory, FlashBagInterface $flashBag/*, ValidatorInterface $validator*/)
    {
        $this->mimeTypes        = ['jpeg','png'];
        $this->photoDirectory   = $photoDirectory;
        $this->flashBag         = $flashBag;
        /*$this->validator        = $validator;*/
    }

    /**
     * @param File $photo
     * @return string
     * @throws \Exception
     */
    public function uploadPhoto(File $photo): ?string
    {
        //dump($photo->getSize());
        if ($this->filterFileSize($photo) && $this->filterMimesTypes($photo)) {
            $fileName = $this->generateUniqueFileName() . '.' .$photo->guessExtension();
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
    private function filterMimesTypes($photo)//: bool
    {dump($photo->guessExtension());
            if (\in_array($photo->guessExtension(), $this->mimeTypes, true)&&
                is_file($photo)) {

                /* if($this->validator->validate($photo, array(
               new Image(array('mimeTypes'   => ['image/jpeg', 'image/png']))
           ))){*/

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
        /*$validator = Validation::createValidator();
        if($validator->validate($photo, array(
            new Image(array('maxSize'   => 1))
        ))){*/
        if ($photo->getSize() < 1000000 &&
                is_file($photo)){

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