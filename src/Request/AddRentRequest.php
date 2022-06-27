<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class AddRentRequest extends BaseRequest
{
    #[Assert\Date]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $startDate;

    #[Assert\Date]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $endDate;

    #[Assert\Type('numeric')]
    #[Assert\NotBlank]
    #[Assert\NotNull]
    private $car;

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param mixed $car
     */
    public function setCar($car): void
    {
        $this->car = $car;
    }
}
