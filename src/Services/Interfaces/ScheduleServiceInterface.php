<?php

namespace App\Services\Interfaces;

use App\Repository\Interfaces\ScheduleRepositoryInterface;
use App\Services\Tools\DatComparator;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Boolean;

interface ScheduleServiceInterface 
{
    /**
     * ScheduleServiceInterface constructor.
     * @param ScheduleRepositoryInterface $ScheduleRepository
     * @param DatComparator $datComparator
     */
    public function __construct(ScheduleRepositoryInterface $ScheduleRepository, DatComparator $datComparator );
    public function isWeeklyBookingClosedDays(DateTimeInterface $expectedDate): Boolean;
    public function isPartTimeBookingClosedForToday(DateTimeInterface $expectedDate , int $partTimeCode = 0) : Boolean;


}