<?php

namespace App\Services;

use App\Entity\Car;
use App\Entity\Image;
use App\Entity\User;
use App\Event\CarEvent;
use App\Mapper\CarMapper;
use App\Repository\CarRepository;
use App\Request\CarRequest;
use App\Transformer\CarTransformer;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CarService
{
    /**
     * @var CarRepository
     */
    private CarRepository $carRepository;

    private CarTransformer $carTransformer;

    private CarMapper $carMapper;

    private ImageService $imageService;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * CarService constructor.
     *
     * @param CarRepository $carRepository
     * @param EventDispatcherInterface $dispatcher
     * @param ImageService $imageService
     * @param CarTransformer $carTransformer
     */
    public function __construct(
        CarRepository            $carRepository,
        EventDispatcherInterface $dispatcher,
        ImageService             $imageService,
        CarTransformer           $carTransformer,
        CarMapper                $carMapper
    )
    {
        $this->imageService = $imageService;
        $this->carRepository = $carRepository;
        $this->dispatcher = $dispatcher;
        $this->carTransformer = $carTransformer;
        $this->carMapper = $carMapper;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getCar(int $carId): Car
    {
        $car = $this->carRepository->find($carId);
        if (!$car) {
            throw new EntityNotFoundException('Car with id ' . $carId . ' does not exist!');
        }
        return $car;
    }

    public function getAllCars(CarRequest $requestData): array
    {
        $cars = $this->carRepository->getAll($requestData);
        $carsData = [];
        foreach ($cars as $car) {
            $carTransform = $this->carTransformer->transform($car);
            $carsData[] = $carTransform;
        }

        return $carsData;
    }

    /**
     * @param Car $car
     * @param User $user
     * @param string $thumbnailURL
     * @return Car
     */
    public function addCar(Car $car, User $user, string $thumbnailURL): Car
    {
        $image = new Image();
        $image->setPath($thumbnailURL);
        $this->imageService->addImage($image);
        $car->setThumbnail($image);
        $car->setCreatedUser($user);
        $this->carRepository->add($car, true);

        $event = new CarEvent($car);
        $this->dispatcher->dispatch($event, CarEvent::SET);

        return $car;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function updateCar(Car $car, array $requestData): Car
    {
        $carUpdate = $this->carMapper->mapping($car, $requestData);
        $this->carRepository->add($carUpdate, true);
        return $carUpdate;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function deleteCar(int $carId): void
    {
        $car = $this->carRepository->find($carId);
        if (!$car) {
            throw new EntityNotFoundException('Car with id ' . $carId . ' does not exist!');
        }
        $this->carRepository->remove($car, true);

        $event = new CarEvent($car);
        $this->dispatcher->dispatch($event, CarEvent::DELETE);
    }

    /**
     * @throws EntityNotFoundException
     */
    public function deleteSoftCar(int $carId): Car
    {
        $car = $this->carRepository->find($carId);
        if (!$car) {
            throw new EntityNotFoundException('Car with id ' . $carId . ' does not exist!');
        }
        $car->setDeletedAt(new \DateTimeImmutable());
        $this->carRepository->add($car, true);
        return $car;
    }
}
