<?php


namespace App\Validator\Constraints\BookingOrders;

use App\Entity\BookingOrder;
use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotUnaccompaniedUnderageValidator extends ConstraintValidator
{
    /** @var ParamServiceInterface */
    private $paramService;

    public function __construct(ParamServiceInterface $paramService)
    {
        $this->paramService = $paramService;
    }

    public function validate($bookingOrder, Constraint $constraint)
    {

        if (!$constraint instanceof NotUnaccompaniedUnderage) {
            throw new UnexpectedTypeException($constraint, NotUnaccompaniedUnderage::class);

        }
        if (!$bookingOrder instanceof BookingOrder) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($bookingOrder, BookingOrder::class);
        }

        if ( count($bookingOrder->getVisitors()) > 0 && $this->paramService->isUnderage($bookingOrder->getGroupMaxAge()) ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}