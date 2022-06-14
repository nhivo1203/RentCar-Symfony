<?php

namespace App\Controller\API;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AdminController extends AbstractController
{
    /**
     * @Route("/api/admin", name="api_admin")
     * @return JsonResponse
     */
    public function admin(): JsonResponse
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->json(['errors' => "Access denied"],
                Response::HTTP_FORBIDDEN);
        }
        return $this->json(['message' => "HELLO ADMIN"]);
    }
}
