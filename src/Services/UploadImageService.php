<?php

namespace App\Services;

use App\Entity\Image;
use App\Manager\FileManager;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadImageService
{
    private $targetDirectory;
    private ImageService $imageService;
    private FileManager $fileManager;

    public function __construct($targetDirectory, ImageService $imageService, FileManager $fileManager)
    {
        $this->targetDirectory = $targetDirectory;
        $this->imageService = $imageService;
        $this->fileManager = $fileManager;
    }

    public function uploadS3Image(?UploadedFile $file): ?Image
    {
        if ($file === null) {
            return null;
        }
        $thumbnailURL = $this->fileManager->handleUpload($file);
        if ($thumbnailURL === null) {
            return null;
        }

        return $this->getImage($thumbnailURL);
    }

    public function uploadLocalImage(?UploadedFile $file): ?Image
    {
        if ($file === null) {
            return null;
        }
        $fileName = uniqid('', true) . '.' . $file->guessExtension();
        try {
            $file->move(
                $this->targetDirectory,
                $fileName
            );
        } catch (FileException $e) {
            return null;
        }
        $fileName = 'images/' . $fileName;

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
