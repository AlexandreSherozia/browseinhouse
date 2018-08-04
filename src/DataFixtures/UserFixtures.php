<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 03/08/2018
 * Time: 00:04
 */

namespace App\DataFixtures;


use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{

    private $numberOfIterations = 20;
    private $encoder;

    private $firstNames = ['John', 'Michael', 'Alex', 'George', 'Colleen','Lara','Larissa','Matilde','Kauan','Maria','Marcos','Emilly','Breno','Joao','Simon','Simon','Sille','Marius','Anna','Peter'];
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



            $user->setEmail($i . 'user@gmail.com');

            $key = array_rand($this->firstNames);
            $randomedFirstName = $this->firstNames[$key];
            $user->setPseudo($i . $randomedFirstName);

            $user->setFirstname($randomedFirstName);

            $key = array_rand($this->lastNames);
            $randomedLastName = $this->lastNames[$key];
            $user->setLastname($randomedLastName);

            $phones     =  '0' . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) .mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9);
            $user->setPhone($phones);



            $pass = $this->encoder->encodePassword($user, 'pepiniere');

            $user->setPassword($pass);

            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
        }

        $manager->flush();
    }

   /* public function getOrder()
    {
        return 1;
    }*/

}