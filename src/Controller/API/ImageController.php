<?php

namespace App\Controller\API;

use App\Services\UploadImageService;
use App\Traits\JsonResponseTrait;
use App\Transformer\ImageTransformer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ImageController extends AbstractController
{
    use JsonResponseTrait;

    /**
     * @IsGranted("ROLE_ADMIN", statusCode=403, message="Access denied")
     * @Route("/api/image", name="api_upload_image", methods={"POST"})
     */
    public function uploadImage(
        Request            $request,
        UploadImageService $uploadImageService,
        ImageTransformer   $imageTransformer

    ): JsonResponse
    {
        $file = $request->files->get('thumbnail');
        $image = $uploadImageService->uploadS3Image($file);

        if ($image === null) {
            return $this->errors('Cannot upload thumbnail now');
        }

        return $this->success($imageTransformer->transform($image));
    }
}
