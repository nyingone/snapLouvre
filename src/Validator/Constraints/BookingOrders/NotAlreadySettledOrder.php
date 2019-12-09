<?php


namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotAlreadySettledOrder extends Constraint
{
    public $message = 'already_settled_order';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}