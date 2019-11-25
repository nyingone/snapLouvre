<?php

namespace App\Validator\Constraints;

use App\Services\Interfaces\ClosingPeriodServiceInterface;
use App\Services\ParamService;
use App\Services\Tools\DatComparator;
use App\Services\ScheduleService;
use App\Services\ClosingPeriodService;
use Symfony\Component\Validator\Constraint;
use App\Validator\Constraints\BookingDateIsOpen;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BookingDateIsOpenValidator extends ConstraintValidator
{
    /** @var ClosingPeriodServiceInterface  */
    protected $closingPeriodService;

    private $closedPeriods = [];
    private $bookingStart;
    private $endOfBooking;

    private $message;

    // public $msgBookingClosedOn = 'booking_closed_booking_period_or_day';
    // public $msgBookingOutOfRange = 'booking_out_of_booking_range';

    public function __construct(ClosingPeriodServiceInterface $closingPeriodService)
    {
        $this->closingPeriodService = $closingPeriodService;
    }

    public function validate($value, Constraint $constraint)
    {

        if (!$constraint instanceof BookingDateIsOpen) {
            throw new UnexpectedTypeException($constraint, BookingDateIsOpen::class);
        }

        if (!is_object($value)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($value, 'date');
        }

        // RETURN TRUE IF chosen Date exceptionnally closed
        if($this->closingPeriodService->isExceptionallyClosed($value))
        {
        $strValue = '???';
        $this->context->buildViolation($constraint->msgBookingClosedOn)
            ->setParameter('{{ string }}', $strValue)
            ->setCode(BookingDateIsOpen::BOOKING_CLOSED_ON)
            ->addViolation();
         }
    }
}