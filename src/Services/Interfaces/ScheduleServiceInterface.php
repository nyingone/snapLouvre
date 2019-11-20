<?php

namespace App\Services\Interfaces;

use App\Repository\Interfaces\ScheduleRepositoryInterface;

interface ScheduleServiceInterface 
{
    public function __construct(ScheduleRepositoryInterface $ScheduleRepository);
}