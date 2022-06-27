<?php

namespace App\Services;

use App\Entity\Rent;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\RentRepository;
use App\Transfer\RentTransfer;

class RentService
{
    private RentTransfer $rentTransfer;
    private RentRepository $rentRepository;
    private CarRepository $carRepository;

    public function __construct(
        RentTransfer $rentTransfer,
        RentRepository $rentRepository,
        CarRepository $carRepository
    ) {
        $this->rentTransfer = $rentTransfer;
        $this->rentRepository = $rentRepository;
        $this->carRepository = $carRepository;
    }

    public function addRent(array $requestData, int $carId, User $user): Rent
    {
        $car = $this->carRepository->find($carId);
        $rent = $this->rentTransfer->transfer($requestData);
        $rent->setCar($car);
        $rent->setUser($user);
        $rent->setStatus('renting');

        $this->rentRepository->add($rent, true);

        return $rent;
    }
}
