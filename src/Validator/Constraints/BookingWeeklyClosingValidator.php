<?php


namespace App\Validator\Constraints;


use App\Services\ScheduleService;
use App\Validator\Constraints\BookingWeeklyClosing;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;


class BookingWeeklyClosingValidator
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof BookingWeeklyClosing) {
            throw new UnexpectedTypeException($constraint, BookingWeeklyClosing::class);
        }

        if (!is_object($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'date');
        }

        // RETURN TRUE IF CLOSED DAYS OF WEEK
        if( $this->scheduleService->isWeeklyBookingClosedDays($value))
        {
            $this->context->buildViolation($constraint->msgBookingWeeklyClosing)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        };

    }
}