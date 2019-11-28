<?php


namespace App\Validator\Constraints\BookingOrders;

use App\Services\Tools\LookUpHolyDays;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotHolyDayValidator extends ConstraintValidator
{
//
    private $message;

    /** @var  LookupHolyDays */
    private $lookUpHolyDays;

    /**
     * NotHolidayValidator constructor.
     * @param LookUpHolyDays $lookUpHolyDays
     */
    public function __construct(LookUpHolyDays $lookUpHolyDays)
    {
        $this->lookUpHolyDays = $lookUpHolyDays;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof NotHolyDay)){
            throw new UnexpectedTypeException($constraint, NotHolyDay::class );
        }
        if (!is_object($value)) {
            throw new UnexpectedValueException($value, \DateTime::class);
        }

        if ($this->lookUpHolyDays->isDateHoliday($value->getTimestamp())) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }

}