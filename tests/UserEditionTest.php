<?php

namespace App\Tests;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserEditionTest extends WebTestCase
{
    public function testUserCantEditProfileWithoutBeingLoggedIn()
    {
        $user = new User();
        $user->setPseudo('pseudoTest');

        $client = static::createClient();
        $client->request('GET', '/edit-profile/pseudoTest');
        $crawler = $client->getCrawler();

        dump($crawler);

        $form = $crawler->selectButton('Edit')->form();
        $form['user[firstname]'] = 'Firstname';
        $form['user[lastname]'] = 'Lastname';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Firstname can\'t contain more than 50 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }


    public function testFirstnameCannotExceedFiftyCharactersInForm()
    {
        $user = new User();
        $user->setPseudo('pseudoTest');

        $client = static::createClient();
        $client->request('GET', '/edit-profile/pseudoTest');
        $crawler = $client->getCrawler();

        dump($crawler);

        $form = $crawler->selectButton('Edit')->form();
        $form['user[firstname]']
            = 'Firstnamediekzldkejejejejejejejejejejejejejejejejej';
        $form['user[lastname]'] = 'Lastname';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Firstname can\'t contain more than 50 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }
}