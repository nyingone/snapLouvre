<?php

namespace App\Services\Interfaces;

use App\Repository\Interfaces\ParamRepositoryInterface;

interface ParamServiceInterface 
{
    /** @param ParamRepositoryInterface */
    public function __construct(ParamRepositoryInterface $paramRepository);
}