<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotClosedPeriod extends Constraint
{
    public $message = 'museum_closed_on_chosen_date_or_period';
}