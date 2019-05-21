<?php declare(strict_types=1);

namespace App\Manager;

use App\Entity\UserToken;
use App\Repository\UserTokenRepository;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserTokenManager
 *
 * @package App\Manager
 */
class UserTokenManager extends AbstractManager
{
    /** @var UserTokenRepository */
    private $userTokenRepository;

    /**
     * UserTokenManager constructor.
     *
     * @param ObjectManager  $objectManager
     * @param UserTokenRepository $userTokenRepository
     */
    public function __construct(ObjectManager $objectManager, UserTokenRepository $userTokenRepository)
    {
        parent::__construct($objectManager);

        $this->userTokenRepository = $userTokenRepository;
    }

    /**
     * @param UserToken $userToken
     */
    public function save(UserToken $userToken): void
    {
        $this->updateEntity($userToken);
    }
}
