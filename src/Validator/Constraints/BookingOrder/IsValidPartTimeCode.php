<?php

namespace App\Validator\Constraints\BookingOrder;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsValidPartTimeCode extends Constraint
{
    public $message = "Choose from the available options.";
}
