<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\ParamServiceInterface;
use App\Repository\Interfaces\PricingRepositoryInterface;
use phpDocumentor\Reflection\Types\Integer;

interface PricingServiceInterface 
{
    /**
     * @param \DateTimeInterface $date
     * @param integer $partTimeCode
     * @param boolean $discounted
     * @param $birthDate
     * @return array               // [tariffCode => cost]
     */
    public function findVisitorTariff(\DateTimeInterface $date , $partTimeCode, $discounted,\DateTimeInterface $birthDate) : array ;
}