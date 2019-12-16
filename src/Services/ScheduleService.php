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
    private $unsupportedBookingDays = [];

    private $allPartTimeVisitingHours = [];

    /** @var Schedule */
    private $schedule;

    private $schedules = [];

    /** @var DatComparator */
    protected $datComparator;


    /** @inherit
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param DatComparator $datComparator
     */
    public function __construct(ScheduleRepositoryInterface $scheduleRepository, DatComparator $datComparator)
    {
        $this->scheduleRepository = $scheduleRepository;
        $this->schedules = $this->scheduleRepository->findAll();
        $this->datComparator = $datComparator;

        foreach ($this->schedules as $schedule) {
            if ($schedule->getOpeningTime() == null && $schedule->getLastEntrytime() == null) {
                $this->unsupportedBookingDays[] = $schedule;
            } else {
                $this->allPartTimeVisitingHours[] = $schedule;
            }
        }
    }


    /** @inheritDoc */
    public function isPartTimeBookingTooLateForToday(DateTimeInterface $expectedDate, int $partTimeCode = 0): bool
    {
        $current_time = date("H:i:s");

        if ($this->datComparator->isEqual($expectedDate)) {

            dd($this->allPartTimeVisitingHours, $current_time);

            foreach ($this->allPartTimeVisitingHours as $schedule) {

                if ($schedule->getPartTimeCode() == $partTimeCode && $schedule->getLastEntryTime()->format("H:i:s") <= $current_time) {

                    return true;
                }
            }
        }
        return false;
    }

        /** @inheritDoc */
        public
        function isUnsupportedBookingDay(DateTimeInterface $expectedDate): bool
        {
            foreach ($this->unsupportedBookingDays as $schedule) {
                if ($schedule->getDayOfWeek() == $this->datComparator->dayOfWeek($expectedDate)):
                    return true;
                endif;
            }
            return false;
        }

    }