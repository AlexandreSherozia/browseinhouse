<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationTest extends WebTestCase
{
    public function testPseudoCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[email]'] = 'unemail@valide.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: pseudo needed!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testPseudoCantHaveLessThanFiveCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'user';
        $form['user[email]'] = 'unmail@test.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('pseudo is too short!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testPseudoCantHaveMoreThanFiftyCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'pseudopseudopseudopseudopseudopseudopseudopseudopse';
        $form['user[email]'] = 'unmail@test.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('pseudo is too long!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testEmailCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: email needed!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testEmailCantHaveMoreThanEightyCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'pseudo';
        $form['user[email]'] = 'unmailunmailunmailunmailunmailunmailunmailunmailunmail@testtesttesttesttesttes.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('email is too long!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testEmailMustBeValidInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unemailquimarchepas';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: email is not valid!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testPasswordCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unmail@test.fr';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: password needed!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testPasswordMustHaveEnoughCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unmail@test.fr';
        $form['user[password]'] = 'p';

        $crawler = $client->submit($form);
        $this->assertContains('password is too short!', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testUserRegistrationFormIsOk()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unemail@valide.fr';
        $form['user[password]'] = 'unmotdepasse';

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('Welcome, you have been registered! You can sign up below', $crawler->filter('div.alert')->text());
    }

    public function testPseudoMustBeUniqueInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unemail2@valide.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Sorry, this pseudo is already used by someone else.', $crawler->filter('div.has-error > span > ul > li')->text());
    }

    public function testEmailMustBeUniqueInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['user[pseudo]'] = 'unpseudo2';
        $form['user[email]'] = 'unemail@valide.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Sorry, this email is already used by someone else.', $crawler->filter('div.has-error > span > ul > li')->text());
    }
}