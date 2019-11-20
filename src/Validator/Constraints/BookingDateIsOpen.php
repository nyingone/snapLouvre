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
    public $msgBookingWeeklyClosing   = 'booking_closed_on_following_days_of_week';
    public $msgBookingClosedToday   = 'booking_ended_for_today';
    
}