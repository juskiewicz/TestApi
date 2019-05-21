<?php declare(strict_types=1);

namespace App\Controller;

use App\Service\LoginService;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends BaseController
{
    /**
     * @Route(
     *     name="login",
     *     methods={"GET"},
     *     path="login"
     * )
     *
     * @param Request      $request
     * @param LoginService $loginService
     *
     * @return Response
     * @throws Exception
     */
    public function users(
        Request $request,
        LoginService $loginService
    ): Response {
        return $loginService->login($request->get('username'), $request->get('password'));
    }
}
