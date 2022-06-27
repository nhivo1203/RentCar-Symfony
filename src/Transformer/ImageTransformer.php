<?php

namespace App\Transformer;

use App\Entity\Image;
use JetBrains\PhpStorm\ArrayShape;

class ImageTransformer
{
    #[ArrayShape(['id' => "int|null", 'path' => "null|string"])]
    public function transform(Image $image): array
    {
        return [
            'id' => $image->getId(),
            'path' => $image->getPath(),
        ];
    }
}
