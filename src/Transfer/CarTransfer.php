<?php

namespace App\Transfer;

use App\Entity\Car;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class CarTransfer
{
    public function transfer(array $requestData): Car
    {
        $car = new Car();
        $car->setName($requestData['name']);
        $car->setDescription($requestData['description']);
        $car->setColor($requestData['color']);
        $car->setBrand($requestData['brand']);
        $car->setSeats($requestData['seats']);
        $car->setYear($requestData['year']);
        $car->setPrice($requestData['price']);

        return $car;
    }
}
