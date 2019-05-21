<?php declare(strict_types=1);

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;

/**
 * Class HttpHelper
 *
 * @package App\Helper
 */
class HttpHelper
{
    /**
     * @param int|string $code
     * @param int        $defaultCode
     *
     * @return int
     */
    public static function getHttpCode(
        $code,
        int $defaultCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): int {
        if (!is_int($code)) {
            return Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        if ($code > 100 && $code < 999) {
            return $code;
        }

        return $defaultCode;
    }
}
