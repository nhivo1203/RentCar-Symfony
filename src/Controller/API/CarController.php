<?php

namespace App\Controller\API;

use App\Repository\CarRepository;
use App\Request\CarRequest;
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
     * @Route("/api/addcar", name="api_add_car")
     * @throws JsonException
     */
    public function createCar(
        Request $request,
        ValidatorInterface $validator,
        CarTransfer $carTransfer,
        CarService $carService,
        CarTransformer $carTransformer
    ): JsonResponse {
        $car = $carTransfer->transfer($request->toArray());
        $userId = $this->getUser()->getId();
        $errors = $validator->validate($car);
        if (count($errors) > 0) {
            return $this->errors('Some fields is blank');
        }
        $thumbnailURL = $request->toArray()['thumbnail'];
        $carService->addCar($car, $userId, $thumbnailURL);
        return $this->success($carTransformer->transform($car), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/car/all", name="api_car_list_all")
     */
    public function getAllCars (
        Request $request,
        ValidatorInterface $validator,
        CarRequest $carRequest,
        CarRepository $carRepository,
        CarTransformer $carTransformer
    ): JsonResponse {
        $query = $request->query->all();
        $listCarsRequest = $carRequest->fromArray($query);
        $errors = $validator->validate($listCarsRequest);
        if (count($errors) > 0) {
            return $this->errors('Some fields is blank');
        }
        $cars = $carRepository->getAll($listCarsRequest);
        if (!$cars) {
            return $this->errors('No cars found', Response::HTTP_NOT_FOUND);
        }
        $carsData = [];
        foreach ($cars as $car) {
            $carTransform = $carTransformer->transform($car);
            $carsData[] = $carTransform;
        }
        return $this->success($carsData);
    }

    /**
     * @Route("/api/car/{id}", name="api_get_car_details")
     * @throws EntityNotFoundException
     */
    public function getCarDetails(int $id, CarService $carService, CarTransformer $carTransformer): JsonResponse
    {
        $car = $carService->getCar($id);
        $carData = $carTransformer->transform($car);
        return $this->success($carData);
    }
}
