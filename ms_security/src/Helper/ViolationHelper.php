<?php declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;

/**
 * Class ViolationHelper
 *
 * @package App\Helper
 */
class ViolationHelper
{
    /**
     * @param string          $msg
     * @param string          $field
     * @param mixed           $invalidValue
     * @param int|null        $plural
     * @param int|null|string $code
     * @param Constraint|null $constraint
     * @param mixed|null      $cause
     *
     * @return ConstraintViolation
     */
    public static function createConstraintViolation(
        string $msg,
        string $field = '',
        $invalidValue = '',
        int $plural = null,
        $code = null,
        Constraint $constraint = null,
        $cause = null
    ): ConstraintViolation {
        return new ConstraintViolation(
            $msg,
            $msg,
            [],
            '',
            $field,
            $invalidValue,
            $plural,
            $code,
            $constraint,
            $cause
        );
    }
}
