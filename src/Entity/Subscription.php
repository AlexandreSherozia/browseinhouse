<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SubscriptionRepository")
 */
class Subscription
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $follower;

    /**
     * @return mixed
     */
    public function getFollower()
    {
        return $this->follower;
    }

    /**
     * @param mixed $follower
     */
    public function setFollower($follower): void
    {
        $this->follower = $follower;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $subscribed;

    /**
     * @return mixed
     */
    public function getSubscribed()
    {
        return $this->subscribed;
    }

    /**
     * @param mixed $subscribed
     */
    public function setSubscribed($subscribed): void
    {
        $this->subscribed = $subscribed;
    }


}
