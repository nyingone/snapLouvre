<?php

namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidPartTimeCode extends Constraint
{
    public $message = "Choose from the available options.";
}
