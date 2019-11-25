<?php


namespace App\Validator\Constraints;

/**
 * @Annotation
 */
class BookingHasPayingVisitor
{
    public const BOOKING_HAS_NO_PAYING_VISITOR = 'booking_has_no_paying_visitor';
    public $msgBookingHasNoPayingVisitor = 'Your order must concern at least one paying visitor';



    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}