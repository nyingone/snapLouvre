<?php

namespace App\Validator\Constraints\BookingOrders;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class IsAllowedGuestNumber extends Constraint
{
    public $message = 'exceeds_maximum_guest_allowed_for_booking';


}