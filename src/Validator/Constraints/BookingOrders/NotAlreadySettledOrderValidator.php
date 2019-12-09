<?php


namespace App\Validator\Constraints\BookingOrders;

use App\Entity\BookingOrder;
use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotAlreadySettledOrderValidator extends ConstraintValidator
{
    public function validate($bookingOrder, Constraint $constraint)
    {

        if (!$constraint instanceof NotAlreadySettledOrder) {
            throw new UnexpectedTypeException($constraint, NotAlreadySettledOrder::class);

        }
        if (!$bookingOrder instanceof BookingOrder) {
            throw new UnexpectedValueException($bookingOrder, BookingOrder::class);
        }

        if ($bookingOrder->getExtPaymentRef() || $bookingOrder->getSettledAt() ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}