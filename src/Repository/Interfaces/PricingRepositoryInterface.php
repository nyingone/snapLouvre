<?php

namespace App\Repository\Interfaces;


interface PricingRepositoryInterface
{
    public function findLastPricing($date, $partTimeCode, $discounted, $yearsOld);
}