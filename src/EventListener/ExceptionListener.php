<?php

namespace App\EventListener;

use App\Traits\JsonResponseTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ExceptionListener
{
    use JsonResponseTrait;

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if ($exception instanceof UnauthorizedHttpException) {
            $response = $this->errors("Unauthorized", Response::HTTP_UNAUTHORIZED);
        }
        if ($exception instanceof HttpExceptionInterface) {
            $response = $this->errors($exception->getMessage(), Response::HTTP_EXPECTATION_FAILED);
        } else {
            $response = $this->errors("Internal error", Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
