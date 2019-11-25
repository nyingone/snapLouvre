<?php


namespace App\Services\Interfaces;


use App\Repository\Interfaces\ClosingPeriodRepositoryInterface;
use App\Services\Tools\DatComparator;

interface ClosingPeriodServiceInterface
{

    /**
     * ClosingPeriodService constructor.
     * @param ClosingPeriodRepositoryInterface $closingPeriodRepository
     * @param DatComparator $datComparator
     */
    public function __construct(ClosingPeriodRepositoryInterface $closingPeriodRepository, DatComparator $datComparator);

}