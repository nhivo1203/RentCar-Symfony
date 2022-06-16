<?php

namespace App\Services;

use App\Entity\Image;
use App\Repository\ImageRepository;

class ImageService
{
    private ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    public function addImage(Image $image): Image
    {
        $this->imageRepository->add($image, true);
        return $image;
    }
}
