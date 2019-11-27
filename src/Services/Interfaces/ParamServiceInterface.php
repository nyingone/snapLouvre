<?php

namespace App\Services\Interfaces;



interface ParamServiceInterface 
{
    /**
     * @param int $value
     * @return bool
     */
    public function isValidPartTimeCode(int $value) : bool;

    /**
     *  Find "MaxVisitors" per day configured by Year, ou yearMonth, or day
     * @return integer
     */
    public function findMaxAllowedGuests(): int;
    /**
     * @param int $value
     * @return bool
     */
    public function isNotAllowedNumberOfGuest(int $value): bool;


    /**
     * @param \DateTimeInterface $expectedDate
     * @return int
     */
    public function findMaxDayEntries(\DateTimeInterface $expectedDate): int;

    /**
     * @param \DateTimeInterface $expectedDate
     * @return bool
     */
    public function isOutOfRangeBooking(\DateTimeInterface $expectedDate): bool;

}
