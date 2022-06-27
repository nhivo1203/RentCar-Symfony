<?php

namespace App\Controller\API;

use App\Request\AddRentRequest;
use App\Services\RentService;
use App\Traits\JsonResponseTrait;
use App\Transformer\ErrorsTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RentController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @IsGranted("ROLE_USER", statusCode=401")
     * @Route("/api/rents", name="api_add_rent", methods={"POST"})
     * @param Request $request
     * @param AddRentRequest $addRentRequest
     * @param ValidatorInterface $validator
     * @param ErrorsTransformer $errorsTransformer
     * @param RentService $rentService
     * @return JsonResponse
     */
    public function addRent(
        Request $request,
        AddRentRequest $addRentRequest,
        ValidatorInterface $validator,
        ErrorsTransformer $errorsTransformer,
        RentService $rentService
    ): JsonResponse {
        $requestData = $request->toArray();
        $user = $this->getUser();
        $addRentRequestData = $addRentRequest->fromArray($requestData);
        $errors = $validator->validate($addRentRequestData);

        if (count($errors) > 0) {
            $errorsData = $errorsTransformer->transfer($errors);
            return $this->errors($errorsData);
        }

        $carId = $requestData['car'];
        $rentService->addRent($requestData, $carId, $user);

        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
