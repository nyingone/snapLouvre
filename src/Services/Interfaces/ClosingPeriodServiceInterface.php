<?php


namespace App\Services\Interfaces;


interface ClosingPeriodServiceInterface
{

    /**
     *   * RETURN TRUE IF chosen Date is closed
     * @param \DateTimeInterface $value
     * @return bool
     */
    public function isClosedPeriod(\DateTimeInterface $value) : bool;

}