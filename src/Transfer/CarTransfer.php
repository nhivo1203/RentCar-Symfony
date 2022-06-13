<?php

namespace App\Transfer;

use App\Entity\Car;
use Symfony\Component\HttpFoundation\Request;

class CarTransfer
{
    public function transfer(Request $request): Car
    {
        $car = new Car();
        $requestData = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $car->setName($requestData['name']);
        $car->setType($requestData['type']);
        $car->setBrand($requestData['brand']);
        $car->setImage($requestData['image']);
        $car->setPrice($requestData['price']);

        return $car;
    }
}
