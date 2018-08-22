<?php

namespace App\Tests;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserEditionTest extends WebTestCase
{
    private function login($username = 'unemail@valide.fr', $password = 'unmotdepasse')
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Register')->form();
        $form['app_login[email]'] = $username;
        $form['app_login[password'] = $password;

        $client->submit($form);
        return $client;
    }

    public function testUserCantEditProfileWithoutBeingLoggedIn()
    {
        $client = static::createClient();
        $client->request('GET', '/edit-profile/pseudoTest');

        $crawler = $client->getCrawler();
        $crawler = $client->followRedirect();

        $this->assertContains(
            'Login',
            $crawler->filter('div > h2')->text()
        );*/
    }

    public function testUserCanAccessToProfileEditionInHisProfile()
    {
        $client = $this->login();
        $crawler = $client->request('GET', '/user-profile/unpseudo');
        $crawler = $client->getCrawler();

        $this->assertContains(
            'Edit your infos',
            $crawler->filter('div.panel > a')->text());
    }
}