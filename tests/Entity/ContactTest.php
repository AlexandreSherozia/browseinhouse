<?php


namespace App\Tests\Entity;


use App\Entity\Contact;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{

    public function testContactCanSetAndGetATitle()
    {
        $contact = new Contact();
        $contact->setMessageTitle('Un titre de message');

        $this->assertEquals('Un titre de message', $contact->getMessageTitle());
    }

    public function testContactCanSetAndGetAMessageBody()
    {
        $contact = new Contact();
        $contact->setMessageBody('Le corps du message');

        $this->assertEquals('Le corps du message', $contact->getMessageBody());
    }

    public function testContactCanSetAndGetAContactingEmail()
    {
        $contact = new Contact();
        $contact->setContactingEmail('unem@il.com');

        $this->assertEquals('unem@il.com', $contact->getContactingEmail());
    }

    public function testContactCanSetAndGetAContactedEmail()
    {
        $contact = new Contact();
        $contact->setContactedEmail('unem@il.com');

        $this->assertEquals('unem@il.com', $contact->getContactedEmail());
    }

    public function testContactCanSetAndGetAContactedPseudo()
    {
        $contact = new Contact();
        $contact->setContactedPseudo('unPseudo');

        $this->assertEquals('unPseudo', $contact->getContactedPseudo());
    }

    public function testContactCanSetAndGetAContactingPseudo()
    {
        $contact = new Contact();
        $contact->setContactingPseudo('unPseudo');

        $this->assertEquals('unPseudo', $contact->getContactingPseudo());
    }

    public function testContactCanSetAndGetAnAdvertSlug()
    {
        $contact = new Contact();
        $contact->setAdvertSlug('un-slug-d-advert');

        $this->assertEquals('un-slug-d-advert', $contact->getAdvertSlug());
    }

    public function testContactCanSetAndGetAnAdvertTitle()
    {
        $contact = new Contact();
        $contact->setAdvertTitle('Un titre d\'advert');

        $this->assertEquals('Un titre d\'advert', $contact->getAdvertTitle());
    }
}