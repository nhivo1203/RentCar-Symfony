<?php

namespace App\Event;

use App\Entity\Car;
use Symfony\Contracts\EventDispatcher\Event;

class CarEvent extends Event
{
    public const SET = 'car.set';
    public const UPDATE = 'car.update';
    public const DELETE = 'car.delete';

    /**
     * @var Car
     */
    public Car $car;

    /**
     * CarEvent constructor.
     * @param Car $car
     */
    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    /**
     * @return Car
     */
    public function getCar(): Car
    {
        return $this->car;
    }
}
