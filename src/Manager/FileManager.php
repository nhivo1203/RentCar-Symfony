<?php

namespace App\Manager;

use Aws\Result;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private string $targetDirectory;
    private string $bucket;
    private string $region;
    private string $key;
    private string $secret;
    private string $version;

    public function __construct(
        $targetDirectory,
        $key,
        $secret,
        $bucket,
        $region,
        $version
    ) {
        $this->targetDirectory = $targetDirectory;
        $this->bucket = $bucket;
        $this->region = $region;
        $this->key = $key;
        $this->secret = $secret;
        $this->version = $version;
    }

    /**
     * @param $file
     * @return string|null
     */
    public function uploadLocal($file): ?string
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

        return 'images/' . $fileName;
    }

    /**
     * @param $file
     * @return Result
     */
    public function setS3Client($file): Result
    {
        $key = basename($file);
        $result = new S3Client([
            'version' => $this->version,
            'region' => $this->region,
            'credentials' => ['key' => $this->key, 'secret' => $this->secret]
        ]);
        $result = $result->putObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'SourceFile' => $file,
        ]);
        unlink($file);
        return $result;
    }

    public function getFilePath(string $fileName): string
    {
        $path = "../public/img/";
        $filename = md5(date('Y-m-d H:i:s:u')) . $fileName;
        return $path . $filename;
    }


    public function uploadToS3(UploadedFile $file): ?string
    {
        $fileName = $file->getClientOriginalName();
        $filePath = $this->getFilePath($fileName);
        if (!move_uploaded_file($file->getPathName(), $filePath)) {
            return null;
        }
        try {
            return $this->setS3Client($filePath)->get('ObjectURL');
        } catch (S3Exception $e) {
            return null;
        }
    }
}
