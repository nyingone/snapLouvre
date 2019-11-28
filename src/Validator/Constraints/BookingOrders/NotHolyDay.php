<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotHolyDay extends Constraint
{
    public $message = 'booking_closed_on_holy_day';
}
