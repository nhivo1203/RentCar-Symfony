<?php

namespace App\Transformer;

use App\Entity\Car;
use JetBrains\PhpStorm\ArrayShape;

class CarTransformer
{
    public function transform(Car $car): array
    {
        return [
            'id' => $car->getId(),
            'name' => $car->getName(),
            'description' => $car->getDescription(),
            'color' => $car->getColor(),
            'brand' => $car->getBrand(),
            'seats' => $car->getSeats(),
            'year' => $car->getYear(),
            'createdUser' => $car->getCreatedUser()->getName(),
            'thumbnail' => $car->getThumbnail()->getPath(),
            'price' => $car->getPrice(),
        ];
    }
}
