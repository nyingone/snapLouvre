<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\ParamServiceInterface;
use App\Repository\Interfaces\PricingRepositoryInterface;

interface PricingServiceInterface 
{
    public function __construct(PricingRepositoryInterface $pricingRepository, ParamServiceInterface $paramService);
}