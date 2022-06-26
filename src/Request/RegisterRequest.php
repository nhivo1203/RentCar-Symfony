<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest extends BaseRequest
{
    #[Assert\Email]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $email;

    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $name;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }
}
