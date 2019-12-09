<?php

namespace App\Validator\Constraints\BookingOrders;

use App\Entity\BookingOrder;

use App\Manager\BookingOrderManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotMultiRegisteredValidator extends ConstraintValidator
{

    /** @var BookingOrderManager  */
    private $bookingOrderManager;

    /**
     * @param BookingOrderManager $bookingOrderManager
     */
    public function __construct(BookingOrderManager $bookingOrderManager)
    {
        $this->bookingOrderManager = $bookingOrderManager;

    }

    public function validate($bookingOrder, Constraint $constraint)
    {
       
        if (!($constraint instanceof NotMultiRegistered))
        {
            throw new UnexpectedTypeException($constraint,  NotMultiRegistered::class);

        }
        if (!($bookingOrder instanceof BookingOrder)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($bookingOrder, BookingOrder::class);
        }
    
        if($this->bookingOrderManager->hasMultiRegisteredVisitor($bookingOrder)) {
            $this->context->buildViolation($constraint->message)
            ->addViolation();
        }
    }


}