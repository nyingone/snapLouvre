<?php

namespace App\Services\Interfaces;

use App\Services\Interfaces\ParamServiceInterface;
use App\Repository\Interfaces\PricingRepositoryInterface;

interface PricingServiceInterface 
{
    /**
     * PricingServiceInterface constructor.
     * @param PricingRepositoryInterface $pricingRepository
     * @param \App\Services\Interfaces\ParamServiceInterface $paramService
     */
    public function __construct(PricingRepositoryInterface $pricingRepository, ParamServiceInterface $paramService);
}