<?php

namespace App\Controller\API;

use App\Repository\CarRepository;
use App\Services\CarService;
use App\Transfer\CarTransfer;
use App\Transformer\CarTransformer;
use Doctrine\ORM\EntityNotFoundException;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private CarTransfer $carTransfer;
    private CarTransformer $carTransformer;
    private CarRepository $carRepository;
    private CarService $carService;


    public function __construct
    (
        CarTransfer    $carTransfer,
        CarRepository  $carRepository,
        CarTransformer $carTransformer,
        CarService     $carService
    )
    {
        $this->carTransfer = $carTransfer;
        $this->carRepository = $carRepository;
        $this->carTransformer = $carTransformer;
        $this->carService = $carService;
    }

    /**
     * @Route("/api/addcar", name="api_add_car")
     * @throws JsonException
     */
    public function createCar(Request $request): JsonResponse
    {
        $car = $this->carTransfer->transfer($request);
        $this->carService->addcar($car);
        return $this->json([
            'status' => 'success',
            'data' => $this->carTransformer->transform($car)
        ], Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/car/all", name="api_car_list_all")
     */
    public function getAllCar(): JsonResponse
    {
        $carsData = [];
        $cars = $this->carRepository->findAll();
        if (!$cars) {
            return $this->json([
                'errors' => 'No cars found'
            ]);
        }
        foreach ($cars as $car) {
            $carTransform = $this->carTransformer->transform($car);
            $carsData[] = $carTransform;
        }
        return new JsonResponse($carsData);
    }

    /**
     * @Route("/api/car/{id}", name="api_get_car_details")
     * @throws EntityNotFoundException
     */
    public function getCarDetails(int $id): JsonResponse
    {
        $car = $this->carService->getCar($id);
        $carData = $this->carTransformer->transform($car);
        return new JsonResponse($carData);
    }
}
