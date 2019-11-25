<?php

namespace App\Services;

use App\Entity\Schedule;
use App\Repository\Interfaces\ScheduleRepositoryInterface;
use App\Services\Interfaces\ScheduleServiceInterface;
use App\Services\Tools\DatComparator;
use DateTimeInterface;
use phpDocumentor\Reflection\Types\Boolean;

class ScheduleService implements ScheduleServiceInterface
{
    /** @var ScheduleRepositoryInterface */
    private $scheduleRepository;
    private $closedDaysOfWeek = [];

    private $allPartTimeVisitingHours = [];

    /** @var Schedule */
    private $schedule;
    
    private $schedules= [];
    
    /** @var DatComparator */
    protected $datComparator;


    /** @inherit */
    public function __construct(ScheduleRepositoryInterface $scheduleRepository, DatComparator $datComparator)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->schedules = $this->scheduleRepository->findAll();
        $this->datComparator = $datComparator;
    
        foreach ($this->schedules as $schedule) {
            if ($schedule->getOpeningTime() == null && $schedule->getLastEntrytime() == null)
            {
                   $this->closedDaysOfWeek[] = $schedule;
            } else {
                  $this->allPartTimeVisitingHours[] = $schedule;
            }
        }
    }

    /** @inheritDoc */
    public function isWeeklyBookingClosedDays(DateTimeInterface $expectedDate): Boolean
    {
        foreach ($this->closedDaysOfWeek as $closedDay) {
            if ($closedDay->getDayOfWeek() == $this->datComparator->dayOfWeek($expectedDate)):
                return true;
            endif;
        }
    }

    /** @inheritDoc */
    public function isPartTimeBookingClosedForToday(DateTimeInterface $expectedDate, int $partTimeCode = 0): Boolean
    {
         foreach ($this->allPartTimeVisitingHours as $schedule) {
            $current_time = date("H:i:s");
            $lastEntryTime = date("H:i:s", time($schedule->getLastEntryTime()));
            dd($lastEntryTime) ;

            if ($schedule->getPartTimeCode() == $partTimeCode && $lastEntryTime <= $current_time) {
                return true;
            }
        }
    }
}