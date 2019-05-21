<?php declare(strict_types=1);

namespace App\Security;

use App\Repository\UserTokenRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

/**
 * Class UserTokenAuthenticator
 *
 * @package App\Security
 */
class UserTokenAuthenticator extends AbstractGuardAuthenticator
{
    /** @var UserTokenRepository */
    private $userTokenRepository;

    /**
     * UserTokenAuthenticator constructor.
     *
     * @param UserTokenRepository $userTokenRepository
     */
    public function __construct(
        UserTokenRepository $userTokenRepository
    ) {
        $this->userTokenRepository = $userTokenRepository;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('Authorization')
            && 0 === strpos($request->headers->get('Authorization'), 'Bearer ');
    }

    /**
     * @param Request $request
     *
     * @return string|null
     */
    public function getCredentials(Request $request): array
    {
        $authorizationHeader = $request->headers->get('Authorization');

        return [
            'token' => substr($authorizationHeader, 7)
        ];
    }

    /**
     * @param mixed                 $credentials
     * @param UserProviderInterface $userProvider
     *
     * @return UserInterface|null
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        $token = $credentials['token'];

        if (null === $token) {
            return null;
        }

        $userToken = $this->userTokenRepository->findOneBy(
            [
                'token' => $token
            ]
        );

        if (!$userToken) {
            throw new CustomUserMessageAuthenticationException(
                'INVALID_API_TOKEN'
            );
        }

        if ($userToken->isExpired()) {
            throw new CustomUserMessageAuthenticationException(
                'TOKEN_EXPIRED'
            );
        }
        return $userToken->getUser();
    }

    /**
     * @param mixed         $credentials
     * @param UserInterface $user
     *
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    /**
     * @param Request        $request
     * @param TokenInterface $token
     * @param string         $providerKey
     *
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): ?Response
    {
        // on success, let the request continue
        return null;
    }

    /**
     * @param Request                 $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(
        Request $request,
        AuthenticationException $exception
    ): ?Response {
        return new JsonResponse([
            'message' => $exception->getMessageKey()
        ], 401);
    }

    /**
     * @param Request                      $request
     * @param AuthenticationException|null $authException
     *
     * @return Response
     */
    public function start(
        Request $request,
        AuthenticationException $authException = null
    ): Response {
        $data = array(
            'message' => 'Authentication Required'
        );

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe(): bool
    {
        return false;
    }
}