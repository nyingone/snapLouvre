<?php


namespace App\Services\Interfaces;


interface ClosingPeriodServiceInterface
{

    /**
     * @return array
     */
    public function findClosedPeriods() : array;
    /**
     * @param \DateTimeInterface $value
     * @return bool
     */
    public function isClosedPeriod(\DateTimeInterface $value) : bool;

}