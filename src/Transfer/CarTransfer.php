<?php

namespace App\Transfer;

use App\Entity\Car;
use JsonException;
use Symfony\Component\HttpFoundation\Request;

class CarTransfer
{
    /**
     * @throws JsonException
     */
    public function transfer(Request $request): Car
    {
        $car = new Car();
        $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
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
