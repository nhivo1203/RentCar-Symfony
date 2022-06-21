<?php

namespace App\Mapper;

use App\Entity\Car;
use App\Repository\ImageRepository;

class CarMapper
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function mapping(Car $car, array $requestData): Car
    {
        $thumbnailId = $requestData['thumbnail'];
        $thumbnail = $this->imageRepository->find($thumbnailId);

        $car->setName($requestData['name'] ?? $car->getName())
            ->setDescription($requestData['description'] ?? $car->getDescription())
            ->setColor($requestData['color'] ?? $car->getColor())
            ->setBrand($requestData['brand'] ?? $car->getBrand())
            ->setSeats($requestData['seats'] ?? $car->getSeats())
            ->setYear($requestData['year'] ?? $car->getYear())
            ->setPrice($requestData['price'] ?? $car->getPrice())
            ->setThumbnail($thumbnail ?? $car->getThumbnail());

        return $car;
    }
}
