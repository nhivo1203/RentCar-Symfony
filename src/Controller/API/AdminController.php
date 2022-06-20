<?php

namespace App\Controller\API;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class AdminController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/admin", name="api_admin")
     * @return JsonResponse
     */
    public function admin(): JsonResponse
    {
        return $this->json(['message' => "HELLO ADMIN"]);
    }
}
