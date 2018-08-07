<?php

namespace App\Form\Handler;


use App\Entity\Contact;
use Swift_Mailer;
use Swift_Message;

class ContactHandler
{
    protected $mailer;
    protected $twig;

    public function __construct(Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

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