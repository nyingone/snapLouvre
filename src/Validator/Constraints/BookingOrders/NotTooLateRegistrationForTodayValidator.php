<?php


namespace App\Validator\Constraints\BookingOrders;


use App\Entity\BookingOrder;
use App\Services\Interfaces\ScheduleServiceInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotTooLateRegistrationForTodayValidator extends ConstraintValidator
{

    /** @var ScheduleServiceInterface   */
    private $scheduleService;

    /**
     * NotTooLateRegistrationValidator constructor.
     * @param ScheduleServiceInterface $scheduleService
     */
    public function __construct(ScheduleServiceInterface $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function validate($bookingOrder, Constraint $constraint)
    {
         if(!$constraint instanceof NotTooLateRegistrationForToday) {
             throw new UnexpectedTypeException($constraint , NotTooLateRegistrationForToday::class);
         }

         if(!$bookingOrder instanceof BookingOrder) {
             throw new UnexpectedValueException($bookingOrder, BookingOrder::class);
         }


        // RETURN TRUE IF Last Entry Time is passed

        if($this->scheduleService->isPartTimeBookingTooLateForToday($bookingOrder->getExpectedDate(), $bookingOrder->getPartTimeCode()))
        {
            $error = ' test';
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}