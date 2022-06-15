<?php

namespace App\Manager\Logger;

use App\Entity\Car;

class CarLogger extends BaseLogger
{
    public const SET = 'SET CAR';
    public const UPDATE = 'UPDATE CAR';
    public const DELETE = 'DELETE CAR';

    /**
     * @param Car $car
     */
    public function carSet(Car $car): void
    {
        //$this->logger->info(self::SET .': ', [$this->user->getName(), $this->user->getRoles()]);
        $this->logger->info(self::SET.': ');
        $this->logger->info($this->serializer->serialize($car, 'json'));
        $this->logger->info('');
    }

    /**
     * @param Car $car
     */
    public function carUpdate(Car $car): void
    {
        // $this->logger->info(self::UPDATE .': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::UPDATE.': ');
        $this->logger->info($this->serializer->serialize($car, 'json'));
        $this->logger->info('');
    }

    /**
     * @param Car $car
     */
    public function carDelete(Car $car): void
    {
        // $this->logger->info(self::DELETE.': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::DELETE.': ');
        $this->logger->info($this->serializer->serialize($car, 'json'));
        $this->logger->info('');
    }
}
