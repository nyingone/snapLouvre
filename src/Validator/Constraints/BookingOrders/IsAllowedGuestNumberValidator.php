<?php

namespace App\Validator\Constraints\BookingOrders;

use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsAllowedGuestNumberValidator extends ConstraintValidator
{
    /** @var ParamServiceInterface  */
    private $paramService;
    private $maxAllowedGuests;

    /**
     * NotAllowedGuestNumberValidator constructor.
     * @param ParamServiceInterface $paramService
     */
    public function __construct(ParamServiceInterface $paramService )
    {
        $this->paramService = $paramService;
      //   $this->maxAllowedGuests = $this->paramService->findMaxAllowedGuests();
    }

    public function validate($value, Constraint $constraint)
    {
        // $this->bookingOrder = $value;
        
        if (!$constraint instanceof  IsAllowedGuestNumber) {
            throw new UnexpectedTypeException($constraint,  IsAllowedGuestNumber::class);
        }

        if (!is_integer( $value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'integer');
        }

        if ($this->paramService->isAllowedNumberOfGuest($value) !== true)
        {
            $this->context->buildViolation($constraint->message)
               // ->setParameter('{{ integer }}', $this->maxBookingVisitors)
                ->setParameter('&max', $this->paramService->findMaxAllowedGuests())
                ->addViolation(); 
        }             
    }


}