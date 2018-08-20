<?php

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCanBeCreated()
    {
        $user = new User();
        $this->assertInstanceOf(User::class, $user);
    }

    public function testUserHasARegistrationDateByDefaultAndCanGetIt()
    {
        $user = new User();

        $this->assertInstanceOf(\DateTime::class, $user->getRegistrationDate());
    }

    public function testUserHasAUserRoleByDefaultAndCanGetIt()
    {
        $user = new User();

        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testUserCanSetNewRoleInsteadOfDefaultRole()
    {
        $user = new User();
        $user->setRoles('ROLE_ADMIN');

        $this->assertEquals(['ROLE_ADMIN'], $user->getRoles());
    }

    public function testUserCanAddANewRole()
    {
        $user = new User();
        $user->addRoles('ROLE_ADMIN');

        $this->assertEquals(['ROLE_USER','ROLE_ADMIN'], $user->getRoles());
    }

    public function testUserCanSetAndGetAnEmail()
    {
        $user = new User();
        $user->setEmail('test@email.com');

        $this->assertEquals('test@email.com', $user->getEmail());
    }

    public function testUserCanSetAndGetAPseudo()
    {
        $user = new User();
        $user->setEmail('Pseudo');

        $this->assertEquals('Pseudo', $user->getEmail());
    }

    public function testUserCanSetAndGetAPassword()
    {
        $user = new User();
        $user->setPassword('motdepasse');

        $this->assertEquals('motdepasse', $user->getPassword());
    }

    public function testUserCanSetAndGetAFirstname()
    {
        $user = new User();
        $user->setFirstname('Firstname');

        $this->assertEquals('Firstname', $user->getFirstname());
    }

    public function testUserCanSetAndGetALastname()
    {
        $user = new User();
        $user->setLastname('Lastname');

        $this->assertEquals('Lastname', $user->getLastname());
    }

    public function testUserCanSetAndGetAPhoneNumber()
    {
        $user = new User();
        $user->setPhone('0123456789');

        $this->assertEquals('0123456789', $user->getPhone());
    }

    public function testUserCanSetAndGetAnAvatar()
    {
        $user = new User();
        $user->setAvatar('pictureName.jpg');

        $this->assertEquals('pictureName.jpg', $user->getAvatar());
    }

}