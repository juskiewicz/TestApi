<?php declare(strict_types=1);

namespace App\Manager;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserManager
 *
 * @package App\Manager
 */
class UserManager extends AbstractManager
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * UserManager constructor.
     *
     * @param ObjectManager  $objectManager
     * @param UserRepository $userRepository
     */
    public function __construct(ObjectManager $objectManager, UserRepository $userRepository)
    {
        parent::__construct($objectManager);

        $this->userRepository = $userRepository;
    }

    /**
     * @return array|User[]
     */
    public function findAll(): array
    {
        return $this->userRepository->findAll();
    }

    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->updateEntity($user);
    }
}
