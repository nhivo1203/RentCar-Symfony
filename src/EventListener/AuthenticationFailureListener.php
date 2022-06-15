<?php

namespace App\EventListener;

use App\Traits\JsonResponseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationFailureListener
{
    use JsonResponseTrait;

    public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event): void
    {
        $response = $this->errors('Credentials invalid');

        $event->setResponse($response);
    }
}
