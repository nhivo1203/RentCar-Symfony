<?php

namespace App\Manager\Logger;

use App\Entity\Rent;

class RentLogger extends BaseLogger
{
    public const SET = 'SET RENT';
    public const UPDATE = 'UPDATE RENT';
    public const DELETE = 'DELETE RENT';

    /**
     * @param Rent $rent
     */
    public function rentSet(Rent $rent): void
    {
        //$this->logger->info(self::SET .': ', [$this->user->getName(), $this->user->getRoles()]);
        $this->logger->info(self::SET . ': ');
        $this->logger->info($this->serializer->serialize($rent, 'json'));
        $this->logger->info('');
    }

    /**
     * @param Rent $rent
     */
    public function rentUpdate(Rent $rent): void
    {
        // $this->logger->info(self::UPDATE .': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::UPDATE . ': ');
        $this->logger->info($this->serializer->serialize($rent, 'json'));
        $this->logger->info('');
    }

    /**
     * @param Rent $rent
     */
    public function rentDelete(Rent $rent): void
    {
        // $this->logger->info(self::DELETE.': ', [$this->user->getUserName(), $this->user->getRoles()]);
        $this->logger->info(self::DELETE . ': ');
        $this->logger->info($this->serializer->serialize($rent, 'json'));
        $this->logger->info('');
    }
}
