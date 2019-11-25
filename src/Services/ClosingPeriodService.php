<?php

namespace App\Services;

use App\Repository\Interfaces\ClosingPeriodRepositoryInterface;
use App\Services\Interfaces\ClosingPeriodServiceInterface;
use App\Services\Tools\DatComparator;

class ClosingPeriodService implements ClosingPeriodServiceInterface
{
    /** @var ClosingPeriodRepositoryInterface  */
    private $closingPeriodRepository;

    /** @var array  */
    private $closingPeriods = [];


    /** @var DatComparator */
    protected $datComparator;

    /** @inheritDoc  */
    public function __construct(ClosingPeriodRepositoryInterface $closingPeriodRepository, DatComparator $datComparator)
        {
            $this->closingPeriodRepository = $closingPeriodRepository;
            $this->datComparator = $datComparator;
            $this->findClosedPeriods();
        }

    /** @return array */
    public function findClosedPeriods() : array
    {
        $this->closingPeriods = $this->closingPeriodRepository
         ->findAll();

        return $this->closingPeriods;
    }

    public function isExceptionallyClosed(\DateTimeInterface $value)
    {
        // $strValue = $this->datComparator->convert($value);
        foreach ($this->closingPeriods as $closedPeriod):
            if ($this->datComparator->isHigherOrEqual($value, $closedPeriod->getFromDat0()) && $this->datComparator->isLowerOrEqual($value, $closedPeriod->getToDatex())):
              return true;
            endif;
        endforeach;

    }

}
    