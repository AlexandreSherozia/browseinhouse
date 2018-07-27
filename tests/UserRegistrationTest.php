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

        $form = $crawler->selectButton('user_submit')->form();
        $form['user[email]'] = 'unemail@valide.fr';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: pseudo needed!', $crawler->filter('ul > li')->text());
    }

    public function testEmailCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('user_submit')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: email needed!', $crawler->filter('ul > li')->text());
    }

    public function testPasswordCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('user_submit')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unmail@test.fr';

        $crawler = $client->submit($form);
        $this->assertContains('Warning: password needed!', $crawler->filter('ul > li')->text());
    }

    public function testUserRegistrationFormIsOk()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('user_submit')->form();
        $form['user[pseudo]'] = 'unpseudo';
        $form['user[email]'] = 'unemail@valide.fr';
        $form['user[password]'] = 'unmotdepasse';

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains('Congratulation, you have been registered !', $crawler->filter('div.alert')->text());
    }

}