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

    /**
     * @param ClosingPeriodRepositoryInterface $closingPeriodRepository
     * @param DatComparator $datComparator
     */
    public function __construct(ClosingPeriodRepositoryInterface $closingPeriodRepository, DatComparator $datComparator)
        {
            $this->closingPeriodRepository = $closingPeriodRepository;
            $this->datComparator = $datComparator;
            $this->findClosedPeriods();
        }

    /**
     * RETURN [ClosePeriod]
     * @inheritDoc
     */
    public function findClosedPeriods() : array
    {
        $this->closingPeriods = $this->closingPeriodRepository
         ->findAll();

        return $this->closingPeriods;
    }

    /**
     * RETURN TRUE IF chosen Date is closed
     * @inheritDoc
     */
    public function isClosedPeriod(\DateTimeInterface $value) : bool
    {
        // $strValue = $this->datComparator->convert($value);
        foreach ($this->closingPeriods as $closedPeriod):
            if ($this->datComparator->isHigherOrEqual($value, $closedPeriod->getFromDat0()) && $this->datComparator->isLowerOrEqual($value, $closedPeriod->getToDatex())):
              return true;
            endif;
        endforeach;

    }

}
    