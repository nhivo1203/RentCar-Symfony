<?php

namespace App\Controller\API;

use App\Repository\CarRepository;
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

class CarController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/addcar", name="api_add_car")
     * @throws JsonException
     */
    public function createCar(
        Request        $request,
        CarTransfer    $carTransfer,
        CarService $carService,
        CarTransformer $carTransformer
    ): JsonResponse
    {
        $car = $carTransfer->transfer($request);
        $userId = $this->getUser()->getId();
        $thumbnailURL = $request->toArray()['thumbnail'];
        $carService->addCar($userId, $thumbnailURL, $car);
        return $this->success($carTransformer->transform($car), Response::HTTP_CREATED);
    }

    /**
     * @Route("/api/car/all", name="api_car_list_all")
     */
    public function getAllCar(CarRepository $carRepository, CarTransformer $carTransformer): JsonResponse
    {
        $carsData = [];
        $cars = $carRepository->findAll();
        if (!$cars) {
            return $this->json([
                'errors' => 'No cars found'
            ]);
        }
        foreach ($cars as $car) {
            $carTransform = $carTransformer->transform($car);
            $carsData[] = $carTransform;
        }
        return new JsonResponse($carsData);
    }

    /**
     * @Route("/api/car/{id}", name="api_get_car_details")
     * @throws EntityNotFoundException
     */
    public function getCarDetails(int $id,CarService $carService ,CarTransformer $carTransformer): JsonResponse
    {
        $car = $carService->getCar($id);
        $carData = $carTransformer->transform($car);
        return new JsonResponse($carData);
    }
}
