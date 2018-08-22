<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture implements OrderedFixtureInterface
{

    private $numberOfIterations = 20;
    private $encoder;

    private $firstNamesF = ['Colleen','Lara','Larissa','Matilde','Kauan','Maria','Emilly','Sille','Anna'];
    private $firstNamesM = ['John', 'Michael', 'Alex', 'George', 'Marcos','Breno','Joao','Simon','Simon','Marius','Peter'];
    private $avatarsF = ['fixture_f1.jpg', 'fixture_f2.jpg', 'fixture_f3.jpg', 'fixture_f4.jpg', 'fixture_f5.jpg', 'fixture_f6.jpg', 'fixture_f7.jpg', 'fixture_f8.jpg', 'fixture_f9.jpg'];
    private $avatarsM = ['fixture_m1.jpg', 'fixture_m2.jpg', 'fixture_m3.jpg', 'fixture_m4.jpg', 'fixture_m5.jpg', 'fixture_m6.jpg', 'fixture_m7.jpg', 'fixture_m8.jpg', 'fixture_m9.jpg', 'fixture_m10.jpg'];
    private $lastNames  = ['Denisov','Yevseyeva','Borodin','Rinkashifu','Hruška','Režná','Beaupré','Duplessis','Jodion','Guérette','Chnadonnet','Boisclair','Lamy','Didiane','Danielsen','Danielsen','Bech','Thom'];

    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * create a random user adapting firstnames & avatars according to a random gender
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < $this->numberOfIterations; $i++)
        {
            $user = new User();
            $user->setEmail('user' .$i . '@gmail.com');

            $number = rand(1,2);
            $gender = ($number < 1.51) ? 'F' : 'M';

            $key = array_rand($this->{'firstNames'.$gender});
            $randomFirstName = $this->{'firstNames'.$gender}[$key];
            $user->setPseudo($randomFirstName .$i);

            $user->setFirstname($randomFirstName);

            $key = array_rand($this->lastNames);
            $randomLastName = $this->lastNames[$key];
            $user->setLastname($randomLastName);

            $key = array_rand($this->{'avatars'.$gender});
            $randomAvatar = $this->{'avatars'.$gender}[$key];
            $user->setAvatar($randomAvatar);

            $phones     =  '0' . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) .mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
            $user->setPhone($phones);

            $pass = $this->encoder->encodePassword($user, 'pepiniere');

            $user->setPassword($pass);

            $user->setFixtureRole('ROLE_USER');

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}