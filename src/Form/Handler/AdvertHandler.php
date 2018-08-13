<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 25/07/2018
 * Time: 14:21
 */

namespace App\Form\Handler;


use App\Entity\Advert;
use App\Entity\Photo;
use App\Service\AdvertManager;
use App\Service\AdvertPhotoUploader;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AdvertHandler
{
    protected   $form,
                $advert,
                $request,
                $currentPhoto,
                $advertManager,
                $advertPhotoUploader;


    /**
     * AdvertHandler constructor.
     * @param $form
     * @param $request
     */
    public function __construct(Form $form, Request $request, AdvertManager $advertManager, AdvertPhotoUploader $advertPhotoUploader)
    {
        $this->form                 = $form;
        $this->request              = $request;
        $this->advertManager        = $advertManager;
        $this->advertPhotoUploader  = $advertPhotoUploader;


    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);



        if ($this->form->isSubmitted() && $this->form->isValid()) {

            $this->currentPhoto = $this->form->get('photos')->getData();
            //dump($this->currentPhoto);

            foreach ($this->currentPhoto as $key => $fileBrut)
            {

                $file = $this->advertPhotoUploader->uploadPhoto($fileBrut);

                $photo = new Photo();

                $photo->setUrl($file);
                $photo->setName($this->form->get('title')->getData() . '-'. ($key+1) );
                $photo->setAdvert($this->form->getData());
                $this->onSubmitted($photo);//onSuccess
            }

            return true;

        }

        return false;

    }


    /**
     * @return Form
     * Advert controller doesn't have "form" anymore to build the form by
     * "form->createview", "getFrom" method provides him with it
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Protected method, gets filled form data and
     *  "myPersist" method, which persists and flushes by native Doctrine methods
     *  of AdvertManager
     */
    protected function onSubmitted($photo) //onSuccess
    {

        $advert = $this->form->getData();

        $this->advert = $this->advertManager->myPersist($advert, $photo);

    }


}