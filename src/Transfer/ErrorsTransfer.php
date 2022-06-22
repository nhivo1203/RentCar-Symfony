<?php

namespace App\Transfer;

class ErrorsTransfer
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
