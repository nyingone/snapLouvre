<?php


namespace App\Validator\Constraints\BookingOrder;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotUnsupportedReservationDay extends Constraint
{
    public $message = 'no_reservation_supported_on_chosen_day';
}