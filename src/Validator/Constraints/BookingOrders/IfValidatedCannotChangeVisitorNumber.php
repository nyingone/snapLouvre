<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IfValidatedCannotChangeVisitorNumber extends Constraint
{
    public $message = 'booking_validated_cannot_change_visitor_number';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}