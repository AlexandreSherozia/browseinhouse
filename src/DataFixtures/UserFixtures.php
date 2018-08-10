<?php

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture implements OrderedFixtureInterface
{

    private $numberOfIterations = 10;
    private $encoder;

    private $firstNamesF = ['Colleen','Lara','Larissa','Matilde','Kauan','Maria','Emilly','Sille','Anna'];
    private $firstNamesM = ['John', 'Michael', 'Alex', 'George', 'Marcos','Breno','Joao','Simon','Simon','Marius','Peter'];
    private $avatarsF = ['f1.jpg', 'f2.jpg', 'f3.jpg', 'f4.jpg', 'f5.jpg', 'f6.jpg', 'f7.jpg', 'f8.jpg', 'f9.jpg'];
    private $avatarsM = ['m1.jpg', 'm2.jpg', 'm3.jpg', 'm4.jpg', 'm5.jpg', 'm6.jpg', 'm7.jpg', 'm8.jpg', 'm9.jpg', 'm10.jpg'];
    private $lastNames  = ['Denisov','Yevseyeva','Borodin','Rinkashifu','Hruška','Režná','Beaupré','Duplessis','Jodion','Guérette','Chnadonnet','Boisclair','Lamy','Didiane','Danielsen','Danielsen','Bech','Thom'];

    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < $this->numberOfIterations; $i++)
        {
            $user = new User();
            $user->setEmail('user' .$i . '@gmail.com');

            $number = rand(1,2);

            if($number < 1.51) {

                $key = array_rand($this->firstNamesF);
                $randomFirstName = $this->firstNamesF[$key];
                $user->setPseudo($randomFirstName .$i);

                $user->setFirstname($randomFirstName);

                $key = array_rand($this->lastNames);
                $randomLastName = $this->lastNames[$key];
                $user->setLastname($randomLastName);

                $key = array_rand($this->avatarsF);
                $randomAvatar = $this->avatarsF[$key];
                $user->setAvatar($randomAvatar);

            }

            else {

                $key = array_rand($this->firstNamesM);
                $randomFirstName = $this->firstNamesM[$key];
                $user->setPseudo($randomFirstName . $i);

                $user->setFirstname($randomFirstName);

                $key = array_rand($this->lastNames);
                $randomLastName = $this->lastNames[$key];
                $user->setLastname($randomLastName);

                $key = array_rand($this->avatarsM);
                $randomAvatar = $this->avatarsM[$key];
                $user->setAvatar($randomAvatar);
            }

            $phones     =  '0' . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) .mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
            $user->setPhone($phones);

            $pass = $this->encoder->encodePassword($user, 'pepiniere');

            $user->setPassword($pass);

            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}