<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotTooLateRegistrationForToday extends Constraint
{
    public $message = 'chosen_part_time_ticket_closed_for_today';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}