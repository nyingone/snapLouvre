<?php


namespace App\Validator\Constraints\BookingOrders;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotOutOfBookingRange extends Constraint
{
    public $message = 'chosen_date_out_of_booking_range';
}