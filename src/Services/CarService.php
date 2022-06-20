<?php

namespace App\Services;

use App\Entity\Car;
use App\Entity\Image;
use App\Event\CarEvent;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CarService
{
    /**
     * @var CarRepository
     */
    private CarRepository $carRepository;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

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
     */
    public function __construct(
        CarRepository $carRepository,
        EventDispatcherInterface $dispatcher,
        UserRepository $userRepository,
        ImageService $imageService
    ) {
        $this->userRepository = $userRepository;
        $this->imageService = $imageService;
        $this->carRepository = $carRepository;
        $this->dispatcher = $dispatcher;
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

    /**
     * @param Car $car
     * @return Car
     */
    public function addCar(Car $car, int $userId, string $thumbnailURL): Car
    {
        $user = $this->userRepository->find($userId);
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
}
