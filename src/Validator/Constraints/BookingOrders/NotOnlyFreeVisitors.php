<?php


namespace App\Validator\Constraints\BookingOrders;

/**
 * @Annotation
 */
use Symfony\Component\Validator\Constraint;

class NotOnlyFreeVisitors extends Constraint
{
    public $message = 'require_at_least_one_paying_visitor';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}