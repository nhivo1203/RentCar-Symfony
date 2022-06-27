<?php

namespace App\Transformer;

class ErrorsTransformer
{
    public function transfer(object $errors): array
    {
        $errorsData = [];

        foreach ($errors as $error) {
            $errorsData[$error->getPropertyPath()] = $error->getMessage();
        }

        return $errorsData;
    }
}
