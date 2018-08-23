<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 21/08/2018
 * Time: 12:06
 */

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Subscription;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

class SubscriptionTest extends TestCase
{
    public function testSubscriptionCanBeCreated()
    {
        $subscription = new Subscription();
        $this->assertInstanceOf(Subscription::class, $subscription);
    }

    public function testFollowerToBeTypeOfUser()
    {
        $subscription   = new Subscription();
        $user = new User();
        $subscription->setFollower($user);
        $this->assertEquals($user, $subscription->getFollower());
    }

    public function testSubscribedToBeTypeOfUser()
    {
        $subscription   = new Subscription();
        $user = new User();
        $subscription->setSubscribed($user);
        $this->assertEquals($user, $subscription->getSubscribed());
    }
}

