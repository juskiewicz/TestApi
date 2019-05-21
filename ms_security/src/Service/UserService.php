<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Manager\UserManager;

/**
 * Class UserService
 *
 * @package App\Services\Feedback
 */
class UserService
{
    /** @var UserManager */
    private $userManager;

    /**
     * UserService constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(
        UserManager $userManager
    ) {
        $this->userManager = $userManager;
    }

    /**
     * @param User $user
     */
    public function addUser(
        User $user
    ): void {
        $this->saveUser($user);
    }

    /**
     * @param User $user
     */
    public function updateUser(User $user): void
    {
        $this->saveUser($user);
    }

    /**
     * @param User $user
     */
    private function saveUser(User $user): void
    {
        $this->userManager->save($user);
    }
}
