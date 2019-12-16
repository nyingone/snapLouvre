<?php


namespace App\Validator\Constraints\BookingOrders;

use App\Services\Interfaces\ClosingPeriodServiceInterface;
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
     * @var ClosingPeriodServiceInterface
     */
    private $closingPeriodService;

    /**
     * NotHolyDayValidator constructor.
     * @param LookUpHolyDays $lookUpHolyDays
     */
    public function __construct(ClosingPeriodServiceInterface $closingPeriodService, LookUpHolyDays $lookUpHolyDays)
    {
        $this->closingPeriodService = $closingPeriodService;
        $this->lookUpHolyDays = $lookUpHolyDays;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof NotHolyDay)) {
            throw new UnexpectedTypeException($constraint, NotHolyDay::class);
        }
        if (!is_object($value)) {
            throw new UnexpectedValueException($value, \DateTime::class);
        }
        if (!($this->closingPeriodService->isClosedPeriod($value))) {

            if ($this->lookUpHolyDays->isDateHolyDay($value->getTimestamp())) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }

}