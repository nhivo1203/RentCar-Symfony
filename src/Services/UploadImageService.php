<?php

namespace App\Services;

use App\Entity\Image;
use App\Manager\FileManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageService
{
    private ImageService $imageService;
    private FileManager $fileManager;

    public function __construct(ImageService $imageService, FileManager $fileManager)
    {
        $this->imageService = $imageService;
        $this->fileManager = $fileManager;
    }

    public function uploadS3Image(?UploadedFile $file): ?Image
    {
        if ($file === null) {
            return null;
        }
        $thumbnailURL = $this->fileManager->uploadToS3($file);
        if ($thumbnailURL === null) {
            return null;
        }

        return $this->getImage($thumbnailURL);
    }

    public function uploadLocalImage(?UploadedFile $file): ?Image
    {
        $fileName = $this->fileManager->uploadLocal($file);
        return $this->getImage($fileName);
    }

    public function getImage(string $fileName): Image
    {
        $image = new Image();
        $image->setPath($fileName);
        $this->imageService->addImage($image);

        return $image;
    }
}
