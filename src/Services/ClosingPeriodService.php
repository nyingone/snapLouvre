<?php

namespace App\Services;

use App\Repository\Interfaces\ClosingPeriodRepositoryInterface;

class ClosingPeriodService

{
    /** @var ClosingPeriodRepositoryInterface  */
    private $closingPeriodRepository;
    /** @var array  */
    private $closingPeriods = [];

        public function __construct(ClosingPeriodRepositoryInterface $closingPeriodRepository)
        {
            $this->closingPeriodRepository = $closingPeriodRepository;
        }

    /** @return array */
    public function findClosedPeriods() : array
    {
        $this->closingPeriods = $this->closingPeriodRepository
         ->findAll();

        return $this->closingPeriods;
    }
    

}
    