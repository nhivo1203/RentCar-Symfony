<?php

namespace App\Transfer;

use App\Entity\User;

class RegisterTransfer
{
    public function transfer(array $requestData): User
    {
        $user = new User();

        $user->setEmail($requestData['email']);
        $user->setName($requestData['name']);

        return $user;
    }
}
