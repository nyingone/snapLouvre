<?php

namespace App\Services\Interfaces;

use App\Repository\Interfaces\ScheduleRepositoryInterface;
use App\Services\Tools\DatComparator;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Boolean;

interface ScheduleServiceInterface 
{
    /**
     * @param DateTimeInterface $expectedDate
     * @return bool
     */
    public function isUnsupportedBookingDay(DateTimeInterface $expectedDate): bool;

    /**
     * @param DateTimeInterface $expectedDate
     * @param int $partTimeCode
     * @return bool
     */
    public function isPartTimeBookingTooLateForToday(DateTimeInterface $getExpectedDate, int $getPartTimeCode): bool;


}