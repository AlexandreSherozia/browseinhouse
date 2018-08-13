<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 08/08/2018
 * Time: 21:29
 */

namespace App\Service;


use Symfony\Component\HttpFoundation\File\File;

/**
 * Class AdvertPhotoUploader
 * @package App\Service
 */
class AdvertPhotoUploader
{
    protected $mimeTypes;
    protected $photoDirectory;
    public const NON_EXISTENT_IMAGE = 'bnh.jpeg';

    /**
     * AdvertPhotoUploader constructor.
     * @param $photo
     */
    public function __construct(string $photoDirectory)
    {
        $this->mimeTypes        = ['jpeg','png'];
        $this->photoDirectory   = $photoDirectory;
    }

    /**
     * @param File $photo
     * @return string
     */
    public function uploadPhoto(File $photo)
    {

        //$photo->getSize();
       /*if (null!==$photo){*/

            foreach ($this->mimeTypes as $mimeType){

                if ($mimeType === $photo->guessExtension()) {
                    $fileName = $this->generateUniqueFileName() . '.' .$photo->guessExtension();

                    $photo->move($this->getPhotoDirectory(), $fileName);

                    return  $fileName;
            }

        }
        return self::NON_EXISTENT_IMAGE;
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