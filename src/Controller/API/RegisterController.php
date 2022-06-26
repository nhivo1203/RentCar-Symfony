<?php

namespace App\Controller\API;

use App\Request\RegisterRequest;
use App\Services\RegisterService;
use App\Traits\JsonResponseTrait;
use App\Transformer\ErrorsTransformer;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @Route("/api/register", name="api_register", methods={"POST"})
     * @throws Exception
     */
    public function register(
        Request $request,
        ValidatorInterface $validator,
        RegisterRequest $registerRequest,
        ErrorsTransformer $errorsTransformer,
        RegisterService $registerService,
    ): JsonResponse {
        $requestData = $request->toArray();
        $registerRequestData = $registerRequest->fromArray($requestData);
        $errors = $validator->validate($registerRequestData);
        if (count($errors) > 0) {
            $errorsData = $errorsTransformer->transfer($errors);
            return $this->errors($errorsData);
        }
        $registerService->register($requestData);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
