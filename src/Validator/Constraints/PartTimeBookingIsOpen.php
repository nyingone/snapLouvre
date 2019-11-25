<?php


namespace App\Validator\Constraints;

/**
 * @Annotation
 */
class PartTimeBookingIsOpen
{
    public const BOOKING_PART_TIME_CLOSED_FOR_TODAY= 'booking_full_time_closed_for_today';
    public $msgPartTimeBookingClosedForToday = 'booking_full_time_closed_for_today';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}