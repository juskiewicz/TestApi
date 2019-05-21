<?php declare(strict_types=1);

namespace App\Service;

use App\Entity\UserToken;
use App\Manager\UserTokenManager;
use App\Repository\UserRepository;
use DateTime;
use Exception;
use DateInterval;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;

/**
 * Class LoginService
 *
 * @package App\Service
 */
class LoginService
{
    /** @var UserTokenManager */
    private $userTokenManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    /**
     * LoginService constructor.
     *
     * @param UserTokenManager             $userTokenManager
     * @param UserRepository               $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        UserTokenManager $userTokenManager,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userTokenManager = $userTokenManager;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param string|null $username
     * @param string|null $password
     *
     * @return Response
     * @throws Exception
     */
    public function login(
        ?string $username,
        ?string $password
    ): Response {
        if (null === $username) {
            throw new HttpException(500,'USERNAME_IS_EMPTY');
        }

        if (null === $password) {
            throw new HttpException(500, 'PASSWORD_IS_EMPTY');
        }

        $user = $this->userRepository->findOneBy(
            [
                'username' => $username
            ]
        );

        if (null === $user ||
            !$this->passwordEncoder->isPasswordValid($user, $password)
        ) {
            throw new AuthenticationCredentialsNotFoundException('LOGIN_ACTION_INCORRECT');
        }

        $expiresOn = new DateTime();
        $expiresOn->add(new DateInterval('P1D'));

        $userToken = new UserToken();
        $userToken
            ->setUser($user)
            ->setToken(bin2hex(random_bytes(60)))
            ->setExpiresOn($expiresOn)
        ;

        $this->userTokenManager->save($userToken);

        $now = new DateTime();

        return new JsonResponse(
            [
                'token' => $userToken->getToken(),
                'expiresIn' => $userToken->getExpiresOn()->getTimestamp() - $now->getTimestamp()
            ]
        );
    }
}
