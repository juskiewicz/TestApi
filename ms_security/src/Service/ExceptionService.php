<?php declare(strict_types=1);

namespace App\Service;

use App\Helper\HttpHelper;
use App\Helper\ViolationHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ExceptionService
 *
 * @package App\Service
 */
class ExceptionService
{
    /** @var Serializer */
    private $serializer;

    /**
     * ExceptionService constructor.
     *
     * @param Serializer $serializer
     */
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param Exception $exception
     *
     * @return Response
     */
    public function prepareExceptionView(Exception $exception): Response
    {
        switch (true) {
            case $exception instanceof NotFoundHttpException:
            case $exception instanceof RouteNotFoundException:
                $constraintViolation = ViolationHelper::createConstraintViolation($exception->getMessage());
                $errorCode = HttpHelper::getHttpCode($exception->getCode(), Response::HTTP_NOT_FOUND);
                break;

            case $exception instanceof HttpException:
                $constraintViolation = ViolationHelper::createConstraintViolation($exception->getMessage());
                $errorCode = HttpHelper::getHttpCode($exception->getStatusCode(), Response::HTTP_BAD_REQUEST);
                break;

            case $exception instanceof MethodNotAllowedException:
                $constraintViolation = ViolationHelper::createConstraintViolation($exception->getMessage());
                $errorCode = HttpHelper::getHttpCode($exception->getCode(), Response::HTTP_METHOD_NOT_ALLOWED);
                break;

            case $exception instanceof AuthenticationCredentialsNotFoundException:
                $constraintViolation = ViolationHelper::createConstraintViolation($exception->getMessage());
                $errorCode = HttpHelper::getHttpCode($exception->getCode(), Response::HTTP_UNAUTHORIZED);
                break;

            default:
                $constraintViolation = ViolationHelper::createConstraintViolation($exception->getMessage());
                $errorCode = HttpHelper::getHttpCode($exception->getCode());
                break;
        }

        return $this->prepareErrorResponse(
            $constraintViolation,
            $errorCode
        );
    }

    /**
     * @param ConstraintViolation|ConstraintViolationList $constraintViolation
     * @param int|null                                    $errorCode
     *
     * @return Response
     */
    public function prepareErrorResponse(
        $constraintViolation,
        ?int $errorCode = Response::HTTP_BAD_REQUEST
    ): Response {
        if ($constraintViolation instanceof ConstraintViolation) {
            $constraintViolationList = new ConstraintViolationList();
            $constraintViolationList->add($constraintViolation);
        } elseif ($constraintViolation instanceof ConstraintViolationList) {
            $constraintViolationList = $constraintViolation;
        } else {
            $constraintViolationList = new ConstraintViolationList();
        }

        $errors = new ArrayCollection();

        foreach ($constraintViolationList as $cv) {
            $errors->add(
                [
                    'propertyPath' => $cv->getPropertyPath(),
                    'invalidValue' => $cv->getInvalidValue(),
                    'message' => $cv->getMessage()
                ]
            );
        }

        return JsonResponse::fromJsonString(
            $this->serializer->serialize(
                ['errors' => $errors],
                'json'
            ),
            $errorCode
        );
    }
}
