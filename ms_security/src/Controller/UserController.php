<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Manager\UserManager;
use App\Serializer\BaseGroups;
use App\Serializer\UserGroups;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{
    /**
     * @Route(
     *     name="getUsers",
     *     methods={"GET"},
     *     path="users"
     * )
     *
     * @param UserManager $userManager
     *
     * @return Response
     */
    public function users(
        UserManager $userManager
    ): Response {
        $users = $userManager->findAll();

        return $this->createResponse(
            Response::HTTP_OK,
            ['users' => $users],
            [BaseGroups::BASE, UserGroups::USER_LIST]
        );
    }

    /**
     * @Route(
     *     name="getUser",
     *     methods={"GET"},
     *     path="users/{userId}",
     *     requirements={"userId": "\d+"}
     * )
     *
     * @param User $user
     *
     * @ParamConverter("user", options={"id" = "userId"})
     *
     * @return Response
     */
    public function user(
        User $user
    ): Response {
        return $this->createResponse(
            Response::HTTP_OK,
            ['user' => $user],
            [BaseGroups::BASE, UserGroups::USER_VIEW]
        );
    }

    /**
     * @Route(
     *     name="postUser",
     *     methods={"POST"},
     *     path="users"
     * )
     *
     * @param Request     $request
     * @param UserService $userService
     *
     * @return Response
     */
    public function userAdd(
        Request $request,
        UserService $userService
    ): Response {
        /** @var User $user */
        $user = $this->getSerializer()->deserialize(
            $request->getContent(),
            User::class,
            'json',
            ['groups' => UserGroups::USER_ADD]
        );

        $errors = $this->validate($user, [UserGroups::USER_ADD]);

        if ($errors->count()) {
            return  $this->createResponse(
                Response::HTTP_BAD_REQUEST,
                ['errors' => $errors]
            );
        }

        $userService->addUser($user);

        return $this->createResponse(
            Response::HTTP_CREATED,
            ['user' => $user],
            [BaseGroups::BASE, UserGroups::USER_ADD]
        );
    }

    /**
     * @Route(
     *     name="patchUser",
     *     methods={"PATCH"},
     *     path="users/{userId}",
     *     requirements={"userId": "\d+"}
     * )
     *
     * @param Request     $request
     * @param UserService $userService
     * @param User        $user
     *
     * @ParamConverter("user", options={"id" = "userId"})
     *
     * @return Response
     */
    public function userEdit(
        Request $request,
        UserService $userService,
        User $user
    ): Response {
        /** @var User $userUpdate */
        $userUpdate = $this->getSerializer()->deserialize(
            $request->getContent(),
            User::class,
            'json',
            [
                'groups' => [
                    BaseGroups::BASE,
                    UserGroups::USER_EDIT
                ],
                'object_to_populate' => $user
            ]
        );

        $errors = $this->validate($userUpdate, [UserGroups::USER_EDIT]);

        if ($errors->count()) {
            return  $this->createResponse(
                Response::HTTP_BAD_REQUEST,
                ['errors' => $errors]
            );
        }

        $userService->updateUser($userUpdate);

        return $this->createResponse(
            Response::HTTP_OK,
            ['user' => $userUpdate],
            [BaseGroups::BASE, UserGroups::USER_EDIT]
        );
    }
}
