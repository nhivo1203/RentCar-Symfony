<?php

namespace App\Controller\API;

use App\Entity\Car;
use App\Request\BaseRequest;
use App\Request\GetCarRequest;
use App\Request\PatchCarRequest;
use App\Request\PutCarRequest;
use App\Services\CarService;
use App\Traits\JsonResponseTrait;
use App\Transfer\CarTransfer;
use App\Transfer\ErrorsTransfer;
use App\Transformer\CarTransformer;
use Doctrine\ORM\EntityNotFoundException;
use JsonException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CarController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars", name="api_add_car", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param CarTransfer $carTransfer
     * @param CarService $carService
     * @param ErrorsTransfer $errorsTransfer
     * @return JsonResponse
     * @throws JsonException
     */
    public function createCar(
        Request $request,
        ValidatorInterface $validator,
        CarTransfer $carTransfer,
        CarService $carService,
        ErrorsTransfer $errorsTransfer,
    ): JsonResponse {
        $car = $carTransfer->transfer($request->toArray());
        $user = $this->getUser();
        $errors = $validator->validate($car);
        if (count($errors) > 0) {
            return $this->errors($errorsTransfer->transfer($errors));
        }
        $thumbnailId = $request->toArray()['thumbnail'];
        $carService->addCar($car, $user, $thumbnailId);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }


    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars/{id}", name="api_put_update_car", methods={"PUT"})
     * @param Car $car
     * @param Request $request
     * @param PutCarRequest $putCarRequest
     * @param ValidatorInterface $validator
     * @param CarService $carService
     * @param ErrorsTransfer $errorsTransfer
     * @return JsonResponse
     */
    public function updatePutCar(
        Car $car,
        Request $request,
        PutCarRequest $putCarRequest,
        ValidatorInterface $validator,
        CarService $carService,
        ErrorsTransfer $errorsTransfer
    ): JsonResponse {
        return $this->updateCar($request, $putCarRequest, $validator, $errorsTransfer, $carService, $car);
    }


    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars/{id}", name="api_patch_update_car", methods={"PATCH"})
     * @param Car $car
     * @param Request $request
     * @param PatchCarRequest $patchCarRequest
     * @param ValidatorInterface $validator
     * @param CarService $carService
     * @param ErrorsTransfer $errorsTransfer
     * @return JsonResponse
     */
    public function updatePatchCar(
        Car $car,
        Request $request,
        PatchCarRequest $patchCarRequest,
        ValidatorInterface $validator,
        CarService $carService,
        ErrorsTransfer $errorsTransfer
    ): JsonResponse {
        return $this->updateCar($request, $patchCarRequest, $validator, $errorsTransfer, $carService, $car);
    }

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars", name="api_delete_car", methods={"DELETE"})
     * @param Car $car
     * @param CarService $carService
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function deleteCar(Car $car, CarService $carService): JsonResponse
    {
        $carService->deleteCar($car);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/api/cars/all", name="api_car_list_all", methods={"GET"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param GetCarRequest $getCarRequest
     * @param ErrorsTransfer $errorsTransfer
     * @param CarService $carService
     * @return JsonResponse
     */
    public function getAllCars(
        Request $request,
        ValidatorInterface $validator,
        GetCarRequest $getCarRequest,
        ErrorsTransfer $errorsTransfer,
        CarService $carService,
    ): JsonResponse {
        $query = $request->query->all();
        $listCarsRequest = $getCarRequest->fromArray($query);
        $errors = $validator->validate($listCarsRequest);
        if (count($errors) > 0) {
            return $this->errors($errorsTransfer->transfer($errors));
        }
        $carsData = $carService->getAllCars($listCarsRequest);
        return $this->success($carsData);
    }

    /**
     * @Route("/api/car/{id<\d+>}", name="api_get_car_details", methods={"GET"})
     * @param int $id
     * @param CarService $carService
     * @param CarTransformer $carTransformer
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws EntityNotFoundException
     */
    public function getCarDetails(int $id, CarService $carService, CarTransformer $carTransformer): JsonResponse
    {
        $car = $carService->getCar($id);
        $carData = $carTransformer->transform($car);
        return $this->success($carData);
    }

    /**
     * @param Request $request
     * @param BaseRequest $updateCarRequest
     * @param ValidatorInterface $validator
     * @param ErrorsTransfer $errorsTransfer
     * @param CarService $carService
     * @param Car $car
     * @return JsonResponse
     */
    private function updateCar(
        Request $request,
        BaseRequest $updateCarRequest,
        ValidatorInterface $validator,
        ErrorsTransfer $errorsTransfer,
        CarService $carService,
        Car $car
    ): JsonResponse {
        $requestData = $request->toArray();
        $updateRequestData = $updateCarRequest->fromArray($requestData);
        $errors = $validator->validate($updateRequestData);
        if (count($errors) > 0) {
            return $this->errors($errorsTransfer->transfer($errors));
        }
        $carService->updateCar($car, $requestData);
        return $this->success([], Response::HTTP_NO_CONTENT);
    }
}
