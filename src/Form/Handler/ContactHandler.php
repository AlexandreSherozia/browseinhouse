<?php

namespace App\Form\Handler;


use App\Entity\Advert;
use App\Entity\Contact;
use App\Entity\User;
use Swift_Mailer;
use Swift_Message;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class ContactHandler
{
    protected $mailer;
    protected $twig;

    public function __construct(Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function process(Form $form, Request $request, Advert $advert, User $contacter, User $contactedUser): bool
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $form->getData()->setContactingEmail($contacter->getEmail())
                ->setContactedEmail($contactedUser->getEmail())
                ->setAdvertSlug($advert->getSlug())
                ->setAdvertTitle($advert->getTitle())
                ->setContactedPseudo($contactedUser->getPseudo())
                ->setContactingPseudo($contacter->getPseudo());

            $this->onSuccess($form);
            return true;
        }

        return false;
    }

    public function onSuccess(Form $form)
    {
        $contact = $form->getData();
        $this->sendMail($contact);
    }

    /**
     * create a mail and send it to as user with user contact form data
     * @param Contact $contact
     */
    public function sendMail(Contact $contact)
    {
        $message = new Swift_Message($contact->getMessageTitle());

        $message->setFrom($contact->getContactingEmail())
            ->setTo($contact->getContactedEmail())
            ->setBody(
                $this->twig->render('mail/usercontact_mail.html.twig', [
                    'contactedpseudo' => $contact->getContactedPseudo(),
                    'contactingpseudo' => $contact->getContactingPseudo(),
                    'advertTitle' => $contact->getAdvertTitle(),
                    'advertSlug' => $contact->getAdvertSlug(),
                    'message' => $contact->getMessageBody()
                ]), 'text/html');

        $this->mailer->send($message);

        return;
    }


}