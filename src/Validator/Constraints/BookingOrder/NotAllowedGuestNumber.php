<?php

namespace App\Validator\Constraints\BookingOrder;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class NotAllowedGuestNumber extends Constraint
{
    public $message = 'exceeds_maximum_guest_allowed_for_booking';


}