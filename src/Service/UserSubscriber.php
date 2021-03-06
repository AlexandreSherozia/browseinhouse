<?php

namespace App\Service;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class UserSubscriber
{
    private $manager;
    private $security;
    private $subscriptionRepository;

    /**
     * UserSubscriber constructor.
     * @param EntityManagerInterface $manager
     * @param Security $security
     */
    public function __construct(EntityManagerInterface $manager, Security $security)
    {
        $this->manager = $manager;
        $this->security = $security;
        $this->subscriptionRepository  = $manager->getRepository(Subscription::class);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function subscriptionRequest(Request $request): bool
    {
        $subscriber = $this->security->getUser();

        if ($request->isXmlHttpRequest()) {
            $star = $this->manager->getRepository(User::class)->findOneBy(['pseudo' => $request->get('pseudo')]);

            if ($this->subscriptionRepository->ifFollowingExists($subscriber, $star)) {
                $existingSubscription = $this->subscriptionRepository->followingRelation($subscriber, $star);

                $this->manager->remove($existingSubscription);
                $this->manager->flush();

                $response = false;
            } else {
                $subscription = new Subscription();
                $subscription->setFollower($subscriber);
                $subscription->setSubscribed($star);
                $this->manager->persist($subscription);
                $this->manager->flush();
                $response = true;
            }
            return $response;
        }

        return false;
    }
}
