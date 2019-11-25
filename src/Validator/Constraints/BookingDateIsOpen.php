<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class BookingDateIsOpen extends Constraint
{
    public const BOOKING_CLOSED_ON = 'booking_closed_booking_period_or_day';
    public $msgBookingClosedOn = 'booking_closed_booking_period_or_day';
}