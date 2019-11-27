<?php


namespace App\Validator\Constraints\BookingOrder;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotOutsideDayBookingQuotas extends Constraint
{
    public $message = 'exceeds_available_booking_on_chosen_date';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}