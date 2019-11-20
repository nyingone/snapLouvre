<?php

namespace App\Validator\Constraints;

use App\Services\ParamService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Validator\Constraints\ BookingWishIsAcceptable;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BookingWishIsAcceptableValidator extends ConstraintValidator
{
    private $paramService;
    private $maxBookingVisitors;

    public function __construct( ParamService $paramService )
    {
        $this->paramService = $paramService;
        $this->maxBookingVisitors = $this->paramService->findMaxBookingVisitors();
    }

    public function validate($value, Constraint $constraint)
    {
        // $this->bookingOrder = $value;
        
        if (!$constraint instanceof  BookingWishIsAcceptable) {
            throw new UnexpectedTypeException($constraint,  BookingWishIsAcceptable::class);
        }

        if (!is_integer( $value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'integer');
        }

        if ($value > $this->maxBookingVisitors)
        {
            $this->context->buildViolation($constraint->msgBookingExcedesAuthorizedWishes)
                ->setParameter('{{ integer }}', $this->maxBookingVisitors)
                ->addViolation(); 
        }             
    }


}