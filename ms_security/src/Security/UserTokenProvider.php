<?php declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Class UserTokenProvider
 *
 * @package App\Security
 */
class UserTokenProvider implements UserProviderInterface
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * UserTokenProvider constructor.
     *
     * @param UserRepository      $userRepository
     */
    public function __construct(
        UserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        return $this->userRepository->findOneBy(
            [
                'username' => $username
            ]
        );
    }

    /**
     * @param UserInterface $user
     */
    public function refreshUser(UserInterface $user): void
    {
        throw new UnsupportedUserException('UNSUPPORTED');
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}