<?php

namespace App\Transformer;

use App\Entity\Car;
use JetBrains\PhpStorm\ArrayShape;

class CarTransformer
{
    #[ArrayShape(['id' => "int|null", 'name' => "null|string", 'brand' => "null|string", 'type' => "null|string", 'image' => "null|string", 'price' => "int|null"])]
    public function transform(Car $car): array
    {
        return [
            'id' => $car->getId(),
            'name' => $car->getName(),
            'brand' => $car->getBrand(),
            'type' => $car->getType(),
            'image' => $car->getImage(),
            'price' => $car->getPrice(),
        ];
    }
}
