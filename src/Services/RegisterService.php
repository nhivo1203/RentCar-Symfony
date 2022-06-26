<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Transfer\RegisterTransfer;
use Exception;

class RegisterService
{
    private UserRepository $userRepository;
    private RegisterTransfer $registerTransfer;
    private SendMailService $sendMailService;

    public function __construct(
        UserRepository $userRepository,
        RegisterTransfer $registerTransfer,
        SendMailService $sendMailService
    ) {
        $this->userRepository = $userRepository;
        $this->registerTransfer = $registerTransfer;
        $this->sendMailService = $sendMailService;
    }

    /**
     * @throws Exception
     */
    public function register(array $requestData): User
    {
        $user = $this->registerTransfer->transfer($requestData);
        $code = $this->generateRandomString();

        $user->setPassword(password_hash($code, PASSWORD_DEFAULT));
        $user->setRoles(['{"roles": "ROLE_USER"}']);

        $emailSubject = 'Welcome to Nhi Vo Rent Car';
        $this->userRepository->add($user, true);
        $this->sendMailService->sendSimpleMail($user->getEmail(), $emailSubject, $code);

        return $user;
    }

    /**
     * @throws Exception
     */
    private function generateRandomString(): string 
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < 6; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
