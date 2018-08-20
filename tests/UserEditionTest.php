<?php

namespace App\Tests;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserEditionTest extends WebTestCase
{
    private function login($username = 'login', $password = 'password')
    {
        // Login...
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Register')->form();
        $form['...'] = $username;

        $client->submit($form);
        //$client->followRedirect();
        return $client;
    }

    public function testUserCantEditProfileWithoutBeingLoggedIn()
    {
        $client = $this->login();
        $client->request('GET', '/edit-profile/pseudoTest');
        $crawler = $client->getCrawler();

        //dump($crawler);

        $crawler = $client->followRedirect();

        $this->assertContains(
            'Login',
            $crawler->filter('div > h2')->text()
        );
    }


//    public function testFirstnameCannotExceedFiftyCharactersInForm()
//    {
//        $client = static::createClient();
//        $client->request('GET', '/edit-profile/pseudoTest');
//        $crawler = $client->getCrawler();
//
//        $form = $crawler->selectButton('Edit')->form();
//        $form['user[firstname]']
//            = 'Firstnamediekzldkejejejejejejejejejejejejejejejejej';
//        $form['user[lastname]'] = 'Lastname';
//
//        $crawler = $client->submit($form);
//        $this->assertContains(
//            'Firstname can\'t contain more than 50 characters',
//            $crawler->filter('div.has-error > span > ul > li')->text()
//        );
//    }
}