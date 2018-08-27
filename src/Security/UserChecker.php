<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 20/08/2018
 * Time: 10:29
 */

namespace App\Security;

use Symfony\Component\Security\Core\Exception\AccountStatusException;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{

    /**
     * Checks the user account before authentication.
     *
     * @param UserInterface $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (!$user->isEnabled()) {
            throw new \RuntimeException("You haven't enabled your account yet");
        }
    }

    /**
     * Checks the user account after authentication.
     *
     * @param UserInterface $user
     */
    public function checkPostAuth(UserInterface $user)
    {
    }
}
