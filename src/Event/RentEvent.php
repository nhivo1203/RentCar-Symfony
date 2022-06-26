<?php

namespace App\Event;

use App\Entity\Rent;
use Symfony\Contracts\EventDispatcher\Event;

class RentEvent extends Event
{
    public const SET = 'rent.set';
    public const UPDATE = 'rent.update';
    public const DELETE = 'rent.delete';

    /**
     * @var Rent
     */
    public Rent $rent;

    /**
     * CarEvent constructor.
     * @param Rent $rent
     */
    public function __construct(Rent $rent)
    {
        $this->rent = $rent;
    }

    /**
     * @return Rent
     */
    public function getRent(): Rent
    {
        return $this->rent;
    }
}
