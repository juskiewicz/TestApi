<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseController
 *
 * @package App\Controller
 */
abstract class BaseController extends AbstractController
{
    /**
     * @param array $data
     * @param array $groups
     *
     * @return string
     */
    protected function serialize(array $data, array $groups): string
    {
        $serializer = $this->getSerializer();

        return $serializer
            ->serialize(
                $data,
                'json',
                ['groups' => $groups]
            );
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        return $this->get(SerializerInterface::class);
    }

    /**
     * @return UserPasswordEncoderInterface
     */
    protected function getPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->get(UserPasswordEncoderInterface::class);
    }

    /**
     * @param      $value
     * @param null $groups
     * @param null $constraints
     *
     * @return ConstraintViolationListInterface
     */
    protected function validate(
        $value,
        $groups = null,
        $constraints = null
    ): ArrayCollection {
        $errors = new ArrayCollection();
        $constraintViolationList = $this->getValidator()->validate($value, $constraints, $groups);

        /** @var ConstraintViolation $constraintViolation */
        foreach ($constraintViolationList as $constraintViolation) {
            $errors->add(
                [
                    'propertyPath' => $constraintViolation->getPropertyPath(),
                    'invalidValue' => $constraintViolation->getInvalidValue(),
                    'message' => $constraintViolation->getMessage()
                ]
            );
        }

        return $errors;
    }

    /**
     * @return ValidatorInterface|object
     */
    protected function getValidator(): ValidatorInterface
    {
        return $this->container->get(ValidatorInterface::class);
    }

    /**
     * @param int   $code
     * @param array $data
     * @param array $groups
     *
     * @return JsonResponse
     */
    protected function createResponse(int $code, array $data, array $groups = []): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->serialize(
                $data,
                $groups
            ),
            $code
        );
    }

    /**
     * @return array
     */
    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services[SerializerInterface::class] = SerializerInterface::class;
        $services[ValidatorInterface::class] = ValidatorInterface::class;
        $services[UserPasswordEncoderInterface::class] = UserPasswordEncoderInterface::class;

        return $services;
    }
}
