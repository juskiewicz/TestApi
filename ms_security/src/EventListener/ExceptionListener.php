<?php declare(strict_types=1);

namespace App\EventListener;

use App\Service\ExceptionService;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

/**
 * Class ExceptionListener
 *
 * @package App\EventListener
 */
class ExceptionListener
{
    /**
     * @var ExceptionService
     */
    private $exceptionService;

    /**
     * ExceptionListener constructor.
     *
     * @param ExceptionService $exceptionService
     */
    public function __construct(
        ExceptionService $exceptionService
    ) {
        $this->exceptionService = $exceptionService;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(
        GetResponseForExceptionEvent $event
    ): void {
        $exception = $event->getException();
        $response = $this->exceptionService->prepareExceptionView($exception);
        $event->setResponse($response);
    }
}
