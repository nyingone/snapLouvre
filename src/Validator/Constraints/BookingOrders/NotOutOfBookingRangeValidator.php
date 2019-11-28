<?php


namespace App\Validator\Constraints\BookingOrders;


use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotOutOfBookingRangeValidator extends ConstraintValidator
{

    /** @var ParamServiceInterface  */
    private $paramService;

    /**
     * NotOutOfBookingRangeValidator constructor.
     * @param ParamServiceInterface $paramService
     */
    public function __construct(ParamServiceInterface $paramService )
    {
        $this->paramService = $paramService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotOutOfBookingRange) {
            throw new UnexpectedTypeException($constraint, NotOutOfBookingRange::class);
        }

        if (!is_object($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'date');
        }


        if($this->paramService->isOutOfRangeBooking($value) )
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}