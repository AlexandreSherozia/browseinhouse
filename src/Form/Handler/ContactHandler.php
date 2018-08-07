<?php

namespace App\Form\Handler;


use App\Entity\Contact;
use Swift_Mailer;
use Swift_Message;

class ContactHandler
{
    protected $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(Contact $contact)
    {
        $message = new Swift_Message($contact->getMessageTitle());

        $message->setFrom($contact->getContactingEmail())
            ->setTo($contact->getContactedEmail())
            ->setBody($contact->getMessageBody());

        $this->mailer->send($message);

        return;
    }


}