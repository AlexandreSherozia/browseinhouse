<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class UserRegistrationTest extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        $client = self::createClient();
        $application = new Application($client->getKernel());
        $application->setAutoExit(false);
        $application->run(new StringInput('doctrine:database:drop --env=test --force'));
        $application->run(new StringInput('doctrine:database:create --env=test'));
        $application->run(new StringInput('doctrine:schema:update --env=test --force'));
    }

    public function testPseudoCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[email]'] = 'unemail@valide.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Pseudo is needed!',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testPseudoCantHaveLessThanFiveCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'user';
        $form['registration_user[email]'] = 'unmail@test.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Pseudo must contain at least 5 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testPseudoCantHaveMoreThanTwentyCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'pseudopseudopseudopse';
        $form['registration_user[email]'] = 'unmail@test.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Pseudo can\'t contain more than 20 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testEmailCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Email is needed!',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testEmailCantHaveMoreThanFiftyCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'pseudo';
        $form['registration_user[email]'] = 'unmailunmailunmailunmailu@testtesttesttesttestts.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Email can\'t contain more than 50 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testEmailMustBeValidInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo';
        $form['registration_user[email]'] = 'unemailquimarchepas';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Email is not valid!',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testPasswordCannotBeBlankInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo';
        $form['registration_user[email]'] = 'unmail@test.fr';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Password is needed!',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testPasswordCantHaveLessThanEightCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo';
        $form['registration_user[email]'] = 'unmail@test.fr';
        $form['registration_user[password]'] = 'passwor';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Password must contain at least 8 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testPasswordCantHaveMoreThanSixtyCharactersInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'pseudo';
        $form['registration_user[email]'] = 'unmail@test.fr';
        $form['registration_user[password]'] =
            '$2y$13$MIZXcVUKh9au30Xrk.zUX.zdkh95bKwOnWCJfC1ZQJ4gZKSSxfXVm2';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Password can\'t contain more than 60 characters',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testUserRegistrationFormIsOk()
    {
        $client = static::createClient();
        $client->request('GET', '/register');

        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo';
        $form['registration_user[email]'] = 'unemail@valide.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $client->submit($form);
        $crawler = $client->followRedirect();

        $this->assertContains(
            'Please, check your mail. A confirmation link has been sent to you.',
            $crawler->filter('div.well > h3')->text()
        );
    }

    public function testPseudoMustBeUniqueInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo';
        $form['registration_user[email]'] = 'unemail2@valide.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Sorry, this pseudo is already used by someone else.',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }

    public function testEmailMustBeUniqueInForm()
    {
        $client = static::createClient();
        $client->request('GET', '/register');
        $crawler = $client->getCrawler();

        $form = $crawler->selectButton('Register')->form();
        $form['registration_user[pseudo]'] = 'unpseudo2';
        $form['registration_user[email]'] = 'unemail@valide.fr';
        $form['registration_user[password]'] = 'unmotdepasse';

        $crawler = $client->submit($form);
        $this->assertContains(
            'Sorry, this email is already used by someone else.',
            $crawler->filter('div.has-error > span > ul > li')->text()
        );
    }
}
