<?php


namespace App\Validator\Constraints\BookingOrder;


use App\Services\ScheduleService;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotUnsupportedReservationDayValidator extends ConstraintValidator
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!($constraint instanceof NotUnsupportedReservationDay)) {
            throw new UnexpectedTypeException($constraint, NotUnsupportedReservationDay::class);
        }

        if (!is_object($value)) {
            throw new UnexpectedValueException($value, 'date');
        }

        // RETURN TRUE IF UNSUPPORTED BOOKING DAY
        if ($this->scheduleService->isUnsupportedBookingDay($value)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}