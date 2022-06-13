<?php

namespace App\Controller\API;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Transformer\CarTransformer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private CarTransformer $carTransformer;
    private CarRepository $carRepository;


    public function __construct(CarRepository $carRepository, CarTransformer $carTransformer)
    {
        $this->carRepository = $carRepository;
        $this->carTransformer = $carTransformer;
    }

    /**
     * @Route("/api/car/all", name="api_car_list_all")
     */
    public function getAllCar(): JsonResponse
    {
        $carsData = [];
        $cars = $this->carRepository->findAll();
        if (!$cars) {
            throw $this->createNotFoundException(
                'No cars found'
            );
        }
        foreach ($cars as $car) {
            $carTransform = $this->carTransformer->transform($car);
            $carsData[] = $carTransform;
        }
        return new JsonResponse($carsData);
    }

    /**
     * @Route("/api/car/{id}", name="api_get_car_details")
     */
    public function getCarDetails( int $id): JsonResponse
    {
        $car = $this->carRepository->find($id);
        if (!$car) {
            throw $this->createNotFoundException(
                'No car found for id ' . $id
            );
        }
        $carData = $this->carTransformer->transform($car);
        return new JsonResponse($carData);
    }
}
