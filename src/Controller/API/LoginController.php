<?php

namespace App\Controller\API;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     * @param JWTTokenManagerInterface $tokenManager
     * @return JsonResponse
     */
    public function login(JWTTokenManagerInterface $tokenManager): JsonResponse
    {
        $user = $this->getUser();
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
        $token = $tokenManager->create($user);
        return $this->json([
            'status' => 'success',
            'data' => [
                'username' => $user->getUserIdentifier(),
                'role' => $user->getRoles(),
                'token' => $token
            ],
        ]);
    }
}
