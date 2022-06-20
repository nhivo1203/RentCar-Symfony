<?php

namespace App\Mapper;

use App\Entity\Car;

class CarMapper
{
    public function mapping(Car $car, array $requestData): Car
    {
        $thumbnail = $car->getThumbnail();
        $car->setName($requestData['name'] ?? $car->getName())
            ->setDescription($requestData['description'] ?? $car->getDescription())
            ->setColor($requestData['color'] ?? $car->getColor())
            ->setBrand($requestData['brand'] ?? $car->getBrand())
            ->setSeats($requestData['seats'] ?? $car->getSeats())
            ->setYear($requestData['year'] ?? $car->getYear())
            ->setPrice($requestData['price'] ?? $car->getPrice())
            ->setThumbnail($thumbnail->setPath($requestData['thumbnail']
                ?? $thumbnail->getPath()));

        return $car;
    }
}
