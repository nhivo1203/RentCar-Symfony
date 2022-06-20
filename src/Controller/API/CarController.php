<?php

namespace App\Controller\API;

use App\Entity\Car;
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
     * @Route("/api/cars/add", name="api_add_car", methods={"POST"})
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
        $thumbnailURL = $request->toArray()['thumbnail'];
        $carService->addCar($car, $user, $thumbnailURL);
        return $this->success($carTransformer->transform($car), Response::HTTP_CREATED);
    }

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/cars/update/{id}", name="api_update_car", methods={"PUT","PATCH"})
     * @throws EntityNotFoundException
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
     * @Route("/api/cars/delete/{id}", name="api_delete_car", methods={"DELETE"})
     * @throws EntityNotFoundException
     */
    public function deleteCar(int $id, CarService $carService): JsonResponse
    {
        $carService->deleteCar($id);
        return $this->success(['message' => 'Car Deleted']);
    }

    /**
     * @Route("/api/car/all", name="api_car_list_all")
     */
    public function getAllCars(
        Request            $request,
        ValidatorInterface $validator,
        CarRequest         $carRequest,
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
        if (!$carsData) {
            return $this->errors('No cars found', Response::HTTP_NOT_FOUND);
        }
        return $this->success($carsData);
    }

    /**
     * @Route("/api/car/{id<\d+>}", name="api_get_car_details")
     * @throws EntityNotFoundException
     */
    public function getCarDetails(int $id, CarService $carService, CarTransformer $carTransformer): JsonResponse
    {
        $car = $carService->getCar($id);
        $carData = $carTransformer->transform($car);
        return $this->success($carData);
    }
}
