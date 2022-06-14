<?php

namespace App\Controller\API;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AdminController extends AbstractController
{
    /**
     * @Route("/api/admin", name="api_admin")
     * @param User|null $user
     * @return JsonResponse
     */
    public function admin(#[CurrentUser] ?User $user): JsonResponse
    {
        return $this->json(['message' => "HELLO ADMIN"]);
    }
}
