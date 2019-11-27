<?php


namespace App\Validator\Constraints\BookingOrder;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotClosedPeriod extends Constraint
{
    public $message = 'museum_closed_on_chosen_date_or_period';
}