<?php


namespace App\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */

class BookingWeeklyClosing
{
    public const BOOKING_WEEKLY_CLOSING = 'booking_closed_on_chosen_days_of_week';
    public $msgBookingWeeklyClosing   = 'booking_closed_on_chosen_days_of_week';


}