<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait JsonResponseTrait
{
    public function success(array $data, int $statusCode = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        return new JsonResponse([
            'status' => "success",
            'data' => $data,
        ], $statusCode, $headers);
    }

    public function errors(
        string $messages,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        array $headers = []
    ): JsonResponse {
        return new JsonResponse([
            'status' => "errors",
            'messages' => $messages,
        ], $statusCode, $headers);
    }
}
