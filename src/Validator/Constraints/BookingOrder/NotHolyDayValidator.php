<?php


namespace App\Validator\Constraints\BookingOrder;

use App\Services\Tools\LookupHolyDays;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotHolyDayValidator extends ConstraintValidator
{
//
    private $message;

    /** @var  LookupHolyDays */
    private $lookupHolyDays;

    /**
     * NotHolidayValidator constructor.
     * @param LookupHolyDays $lookupHolyDays
     */
    public function __construct(LookupHolyDays $lookupHolyDays)
    {
        $this->$lookupHolyDays = $lookupHolyDays;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof NotHolyDay)){
            throw new UnexpectedTypeException($constraint, NotHolyDay::class );
        }
        if (!is_object($value)) {
            throw new UnexpectedValueException($value, \DateTime::class);
        }

        if ($this->lookupHolyDays->isDateHoliday($value->getTimestamp())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }

}