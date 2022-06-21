<?php

namespace App\Controller\API;

use App\Entity\Car;
use App\Request\GetCarRequest;
use App\Services\CarService;
use App\Traits\JsonResponseTrait;
use App\Transfer\CarTransfer;
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
     * @throws JsonException
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param CarTransfer $carTransfer
     * @param CarService $carService
     * @param CarTransformer $carTransformer
     * @return JsonResponse
     * @throws JsonException
     */
    public function createCar(
        Request            $request,
        ValidatorInterface $validator,
        CarTransfer        $carTransfer,
        CarService         $carService,
        CarTransformer     $carTransformer
    ): JsonResponse
    {
        $car = $carTransfer->transfer($request->toArray());
        $user = $this->getUser();
        $errors = $validator->validate($car);
        if (count($errors) > 0) {
            return $this->errors('Some fields is blank');
        }
        $thumbnailId = $request->toArray()['thumbnail'];
        $carService->addCar($car, $user, $thumbnailId);
        return $this->success($carTransformer->transform($car), Response::HTTP_CREATED);
    }

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars", name="api_update_car", methods={"PUT","PATCH"})
     * @param Car $car
     * @param Request $request
     * @param CarService $carService
     * @param CarTransformer $carTransformer
     * @return JsonResponse
     */
    public function updateCar(
        Car         $car,
        Request     $request,
        CarService  $carService,
        carTransformer $carTransformer
    ): JsonResponse
    {
        $requestData =$request->toArray();
        $carUpdated = $carService->updateCar($car, $requestData);
        return $this->success($carTransformer->transform($carUpdated));
    }

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars", name="api_delete_car", methods={"DELETE"})
     * @throws EntityNotFoundException
     * @param int $id
     * @param CarService $carService
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function deleteCar(int $id, CarService $carService): JsonResponse
    {
        $carService->deleteCar($id);
        return $this->success(['message' => 'Car Deleted']);
    }

    /**
     * @Route("/api/cars/all", name="api_car_list_all", methods={"GET"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param GetCarRequest $carRequest
     * @param CarService $carService
     * @return JsonResponse
     */
    public function getAllCars(
        Request            $request,
        ValidatorInterface $validator,
        GetCarRequest      $carRequest,
        CarService         $carService,
    ): JsonResponse
    {
        $query = $request->query->all();
        $listCarsRequest = $carRequest->fromArray($query);
        $errors = $validator->validate($listCarsRequest);
        if (count($errors) > 0) {
            return $this->errors('Some fields is blank');
        }
        $carsData = $carService->getAllCars($listCarsRequest);
        return $this->success($carsData);
    }

    /**
     * @Route("/api/car/{id<\d+>}", name="api_get_car_details", methods={"GET"})
     * @throws EntityNotFoundException
     * @param int $id
     * @param CarService $carService
     * @param CarTransformer $carTransformer
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function getCarDetails(int $id, CarService $carService, CarTransformer $carTransformer): JsonResponse
    {
        $car = $carService->getCar($id);
        $carData = $carTransformer->transform($car);
        return $this->success($carData);
    }
}
