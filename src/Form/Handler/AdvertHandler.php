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
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class AdvertHandler
{
    protected   $form,
                $advert,
                $request,
                $flashBag,
                $currentPhoto,
                $advertManager,
                $advertPhotoUploader;


    /**
     * AdvertHandler constructor.
     * @param Form $form
     * @param Request $request
     * @param AdvertManager $advertManager
     * @param AdvertPhotoUploader $advertPhotoUploader
     * @param FlashBagInterface $flashBag
     */
    public function __construct(Form $form, Request $request, AdvertManager $advertManager, AdvertPhotoUploader $advertPhotoUploader, FlashBagInterface $flashBag)
    {
        $this->form                 = $form;
        $this->request              = $request;
        $this->advertManager        = $advertManager;
        $this->advertPhotoUploader  = $advertPhotoUploader;
        $this->flashBag             = $flashBag;

    }

    /**
     * @return bool
     */
    public function process()
    {
        $this->form->handleRequest($this->request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            $this->currentPhoto = $this->form->get('photos')->getData();

            if($this->currentPhoto){

                if ($this->threePhotosAtMost($this->currentPhoto)){

                    foreach($this->currentPhoto as $key => $fileBrut){


                        $file = $this->advertPhotoUploader->uploadPhoto($fileBrut);

                        if ($file)
                        {
                            $photo = new Photo();
                            $photo->setUrl($file);
                            $photo->setName($this->form->get('title')->getData() . '-'. ($key+1) );
                            $photo->setAdvert($this->form->getData());
                            $this->onSubmittedWithPhoto($photo);

                        } else {

                            return false;
                        }
                    }

                    return true;

                }

                return false;
            }

            /*Si on veut rendre la photo obligatoire, effacer les 2 lignes(retourner false) d'en bas et générer un message*/
                $this->onSubmitted();
                return true;

        }

        return false;

    }

    /**
     * @param array $currentPhoto
     * @return bool
     * Checks files count
     */
    private function threePhotosAtMost(array $currentPhoto): bool
    {

        if (\count($currentPhoto) <= 3)
        {
            return true;
        }

        $this->flashBag->set('error', '3 pictures at most');

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

    protected function onSubmittedWithPhoto($photo)
    {
        $advert = $this->form->getData();
        $this->advertManager->myPersistWithPhoto($advert, $photo);
    }

    /**
     * Protected method, gets filled form data and
     * "myPersist" method, which persists and flushes by native Doctrine methods
     *  of AdvertManager
     */
    protected function onSubmitted() //onSuccess
    {
        $advert = $this->form->getData();
        $this->advertManager->myPersist($advert);
    }


}