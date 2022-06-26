<?php

namespace App\Transfer;

use App\Entity\Rent;

class RentTransfer
{
    public function transfer(array $requestData): Rent
    {
        $rent = new Rent();

        $rent->setStartDate($requestData['startDate']);
        $rent->setEndDate($requestData['endDate']);

        return $rent;
    }
}
