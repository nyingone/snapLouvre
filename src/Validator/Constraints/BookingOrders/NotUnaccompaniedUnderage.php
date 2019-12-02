<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotUnaccompaniedUnderage extends Constraint
{
    public $message = 'unaccompanied_underage_visitor_not_allowed';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}