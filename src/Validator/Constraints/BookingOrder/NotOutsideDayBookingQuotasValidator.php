<?php


namespace App\Validator\Constraints\BookingOrder;


use App\Entity\BookingOrder;
use App\Manager\BookingOrderManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotOutsideDayBookingQuotasValidator extends ConstraintValidator
{
    /** @var BookingOrderManager  */
    private $bookingOrderManager;

    public function __construct(BookingOrderManager $bookingOrderManager)
    {
        $this->bookingOrderManager = $bookingOrderManager;
    }


    public function validate($bookingOrder, Constraint $constraint)
    {

        if (!$constraint instanceof NotOutsideDayBookingQuotas)
        {
            throw new UnexpectedTypeException($constraint,  NotOutsideDayBookingQuotas::class);

        }

        if (!$bookingOrder instanceof BookingOrder) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($bookingOrder, BookingOrder::class);
        }

        if($this->bookingOrderManager->cannotProvideEnoughTickets($bookingOrder)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}