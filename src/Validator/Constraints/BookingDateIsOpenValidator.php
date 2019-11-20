<?php

namespace App\Validator\Constraints;

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
    protected $closingPeriodService;
    private $closedPeriods = [];
    private $bookingStart ;
    private $endOfBooking ;

    protected $paramService;

    protected $scheduleService;
    private $closedDays = [];
    private $todayVisitingHours;
    protected $datComparator;

    private $message;

    // public $msgBookingClosedOn = 'booking_closed_booking_period_or_day';
    // public $msgBookingOutOfRange = 'booking_out_of_booking_range';

    public function __construct(ClosingPeriodService $closingPeriodService, ParamService $paramService, ScheduleService $scheduleService, DatComparator $datComparator)
    {
        $this->closingPeriodService = $closingPeriodService;
        $this->paramService = $paramService;
        $this->scheduleService = $scheduleService;
        $this->datComparator = $datComparator;
        $this->findDefault(); 
     
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


        // if ($value == null):
        //    return (['danger', 'Choose a day of visit!']);
        // endif;
        $strValue = $this->datComparator->convert($value);
        $sel = true;  

        if( $this->datComparator->isLower($value , $this->bookingStart) || $this->datComparator->isHigher($value , $this->endOfBooking) ):
            $sel = false;  
        endif;

        if(is_array($this->closedPeriods)):
            foreach($this->closedPeriods as $closedPeriod):
                if( $this->datComparator->isHigherOrEqual($value , $closedPeriod->getFromDat0()) && $this->datComparator->isLowerOrEqual($value , $closedPeriod->getToDatex()) ):
                $sel = false;     
                endif;
            endforeach;
        endif;

        if ($sel == false):
            $this->context->buildViolation($constraint->msgBookingClosedOn)
            ->setParameter('{{ string }}',$strValue)
            ->setCode(BookingDateIsOpen::BOOKING_CLOSED_ON)
            ->addViolation();  
        else:
            if(is_array($this->closedDays)):
                foreach($this->closedDays as $closedDay):
                    if( $closedDay->getDayOfWeek() == $this->datComparator->dayOfWeek($value) ):
                        $sel = false;
                    endif;
                endforeach;
            endif;

            if ($sel == false):
                $this->context->buildViolation($constraint->msgBookingWeeklyClosing)
                ->setParameter('{{ string }}', $strValue)
                ->addViolation();  
            else:
               // var_dump($this->todayVisitingHours);
                if ($this->datComparator->isEqual($value) && $this->todayVisitingHours == null ): 
                    $this->context->buildViolation($constraint->msgBookingClosedToday)
                    ->setParameter('{{ string }}', $strValue)
                    ->addViolation();  
                endif;
            endif;
        endif;
            
    }

    public function findDefault()
    {
         // GET CLOSED BOOKING PERIODS 
        $this->closedPeriods = $this->closingPeriodService->findClosedPeriods();

        $this->bookingStart = $this->paramService->findstartOfBooking();

         // GET  END of BOOKING (DLY or imperative)
        $this->endOfBooking = $this->paramService->findEndOfBooking();

         // GET CLOSED DAYS OF WEEK
         $this->closedDays = $this->scheduleService->findClosedDays();

         // FIND TODAY Allowed Booking versus VisitingHours
         $this->todayVisitingHours = $this->scheduleService->findTodayVisitingHours();
    }
}