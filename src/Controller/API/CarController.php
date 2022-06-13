<?php

namespace App\Controller\API;

use App\Repository\CarRepository;
use App\Transfer\CarTransfer;
use App\Transformer\CarTransformer;
use Doctrine\ORM\EntityManagerInterface;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CarController extends AbstractController
{
    private CarTransfer $carTransfer;
    private CarTransformer $carTransformer;
    private CarRepository $carRepository;
    private EntityManagerInterface $entityManager;


    public function __construct
    (
        CarTransfer            $carTransfer,
        CarRepository          $carRepository,
        CarTransformer         $carTransformer,
        EntityManagerInterface $entityManager
    )
    {
        $this->carTransfer = $carTransfer;
        $this->carRepository = $carRepository;
        $this->carTransformer = $carTransformer;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/addcar", name="api_add_car")
     * @throws JsonException
     */
    public function createCar(Request $request): JsonResponse
    {
        $car = $this->carTransfer->transfer($request);
        $this->entityManager->persist($car);
        $this->entityManager->flush();
        return new JsonResponse(['data' => $this->carTransformer->transform($car)]);
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
    public function getCarDetails(int $id): JsonResponse
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
