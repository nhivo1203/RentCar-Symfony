<?php

namespace App\Controller\API;

use App\Services\SendMailService;
use App\Traits\JsonResponseTrait;
use PHPMailer\PHPMailer\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMailController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/mail/", name="send_mail", methods={"POST"})
     * @throws Exception
     */
    public function sendEmail(
        Request $request,
        SendMailService $emailService
    ): JsonResponse {
        $email = $request->toArray()['email'];
        $emailService->sendSimpleMail($email);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
