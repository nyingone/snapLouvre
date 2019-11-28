<?php


namespace App\Validator\Constraints\BookingOrders;


use App\Services\Interfaces\ClosingPeriodServiceInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotClosedPeriodValidator extends ConstraintValidator
{
    /** @var ClosingPeriodServiceInterface  */
    protected $closingPeriodService;

    private $closedPeriods = [];

    // private $bookingStart;
    // private $endOfBooking;

    private $message;


    public function __construct(ClosingPeriodServiceInterface $closingPeriodService)
    {
        $this->closingPeriodService = $closingPeriodService;
    }

    public function validate($value, Constraint $constraint)
    {

        if (!($constraint instanceof NotClosedPeriod)) {
            throw new UnexpectedTypeException($constraint, NotClosedPeriod::class);
        }

        if (!is_object($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'date');
        }


        if($this->closingPeriodService->isClosedPeriod($value))
        {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}