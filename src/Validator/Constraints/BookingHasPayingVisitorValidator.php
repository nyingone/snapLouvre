<?php


namespace App\Validator\Constraints;

use App\Entity\BookingOrder;
use App\Manager\BookingOrderManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BookingHasPayingVisitorValidator
{
    /** @var BookingOrderManager  */
    private $bookingOrderManager;

    public function __construct(BookingOrderManager $bookingOrderManager)
    {
        $this->bookingOrderManager = $bookingOrderManager;
    }

    public function validate(BookingOrder $bookingOrder, Constraint $constraint)
    {

        if (!$constraint instanceof BookingHasPayingVisitor) {
            throw new UnexpectedTypeException($constraint, BookingHasPayingVisitor::class);

        }
        if (!$bookingOrder instanceof BookingOrder) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($bookingOrder, BookingOrder);
        }

        if ( $this->bookingOrderManager->hasNoPayingVisitor($bookingOrder) ) {
            $this->context->buildViolation($constraint->msgBookingHasNoPayingVisitor)
                ->setParameter('{{ add a paid entry to free visit for underage kid  }}', "")
                ->addViolation();
        }
    }

}