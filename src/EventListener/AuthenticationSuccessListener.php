<?php

namespace App\EventListener;

use App\Traits\JsonResponseTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

class AuthenticationSuccessListener
{
    use JsonResponseTrait;

    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event): void
    {
        $token = $event->getData()['token'];
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }
        $userJson = $user->jsonSerialize();
        $userJson['token'] = $token;
        $data = [
            'status' => 'success',
            'data' => $userJson
        ];

        $event->setData($data);
    }
}
