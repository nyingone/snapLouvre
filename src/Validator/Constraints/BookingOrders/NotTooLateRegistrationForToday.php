<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTooLateRegistrationForToday extends Constraint
{
    public $message = 'part_or_full_time_booking_closed_for_today';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}