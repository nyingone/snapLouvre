<?php

namespace App\Services;

use App\Services\Interfaces\ScheduleServiceInterface;
use App\Repository\Interfaces\ScheduleRepositoryInterface;

class ScheduleService implements ScheduleServiceInterface
{
  private $scheduleRepository;
  private $closedDaysOfWeek = [];
  private $allPartTimeVisitingHours = [];
  private $partTimeVisitingHours = [];
  private $todayVisitingHours = [];
  private $schedules;

  /** @inherit */
  public function __construct(ScheduleRepositoryInterface $scheduleRepository)
  {
    $this->scheduleRepository = $scheduleRepository;
    $this->schedules = $this->scheduleRepository->findAll();

    

    foreach($this->schedules as $schedule)
    {
      if($schedule->getOpeningTime() == null):
        $this->closedDaysOfWeek[] = $schedule;
      else:
        $this->allPartTimeVisitingHours[] = $schedule;
      endif;
    }
  
    
  }


  public function findClosedDays():array
  {
    return $this->closedDaysOfWeek;
  }


  public function findVisitingHours():array
  {  
    return $this->allPartTimeVisitingHours;
  }


  public function findTodayVisitingHours():array
  {
    $current_time = date("H:i:s");
    // dd($current_time);

    foreach ($this->allPartTimeVisitingHours as $schedule)
    {
      $lastEntryTime = date("H:i:s", time($schedule->getLastEntryTime()));
        // dd($current_time,$schedule->getLastEntryTime(),  $lastEntryTime);
        if ($current_time < $schedule->getLastEntryTime()):
          $this->todayVisitingHours[] = $schedule;
        endif;
    }
    return $this->todayVisitingHours;
  }
    
}