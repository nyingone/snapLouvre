<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;


/**
 * @Annotation
 */
class BookingWishIsAcceptable extends Constraint
{
    public const BOOKING_EXCEDES_AUTORIZED_WISHES = 'booking_demand_excedes_allowed_wishes';
    public $msgBookingExcedesAuthorizedWishes = 'Your demand exceeds max visitors allowed per booking';  
}