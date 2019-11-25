<?php


namespace App\Validator\Constraints;


use App\Entity\BookingOrder;
use App\Services\Interfaces\ScheduleServiceInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PartTimeBookingIsOpenValidator
{

    /** @var ScheduleServiceInterface   */
    private $scheduleService;

    public function __construct(ScheduleServiceInterface $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function validate($bookingOrder, Constraint $constraint)
    {
         if(!$constraint instanceof PartTimeBookingIsOpen) {
             throw new UnexpectedTypeException($constraint , PartTimeBookingIsOpen::class);
         }

         if(!$bookingOrder instanceof BookingOrder) {
             throw new UnexpectedValueException($bookingOrder, BookingOrder::class);
         }


        // RETURN TRUE IF Last Entry Time is passed
        if($this->scheduleService->isPartTimeBookingClosedForToday($bookingOrder->getExpectedDate(), $bookingOrder->getPartTimeCode()))
        {
            $error = ' test';
            $this->context->buildViolation($constraint->msgPartTimeBookingClosedForToday)
                ->setParameter('{{ string }}', $error)
                ->addViolation();
        }
    }
}