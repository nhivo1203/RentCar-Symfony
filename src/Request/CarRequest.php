<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CarRequest extends BaseRequest
{
    #[Assert\Blank]
    private ?string $color = '';

    #[Assert\Type('string')]
    private ?string $brand = '';

    #[Assert\Type('int')]
    private ?int $seats = 0;

    #[Assert\Type('int')]
    private ?int $limit = 10;

    #[Assert\Choice(
        choices: ['createdAt', 'price'],
    )]
    private ?string $orderBy = 'createdAt.asc';

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->color = $color;
    }

    /**
     * @return string|null
     */
    public function getBrand(): ?string
    {
        return $this->brand;
    }

    /**
     * @param string|null $brand
     */
    public function setBrand(?string $brand): void
    {
        $this->brand = $brand;
    }

    /**
     * @return int|null
     */
    public function getSeats(): ?int
    {
        return $this->seats;
    }

    /**
     * @param int|null $seats
     */
    public function setSeats(?int $seats): void
    {
        $this->seats = $seats;
    }

    /**
     * @return int|null
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     */
    public function setLimit(?int $limit): void
    {
        $this->limit = $limit;
    }
}
