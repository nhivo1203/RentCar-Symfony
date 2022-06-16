<?php

namespace App\Services;

use App\Entity\Car;
use App\Event\CarEvent;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class CarService
{
    /**
     * @var CarRepository
     */
    private CarRepository $carRepository;
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
    public function __construct(CarRepository $carRepository, EventDispatcherInterface $dispatcher)
    {
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
    public function addCar(Car $car): Car
    {
        var_dump($car);die();
        $this->carRepository->add($car, true);
        $event = new CarEvent($car);
        $this->dispatcher->dispatch($event, CarEvent::SET);
        return $car;
    }
}
