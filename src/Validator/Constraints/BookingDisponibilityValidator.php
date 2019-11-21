<?php

namespace App\Validator\Constraints;

use App\Entity\BookingOrder;
use App\Manager\BookingOrderManager;
use App\Services\Interfaces\ParamServiceInterface;
use App\Services\ParamService;

use App\Services\Tools\DatComparator;
use Symfony\Component\Validator\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use App\Validator\Constraints\BookingDisponibility;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class BookingDisponibilityValidator extends ConstraintValidator
{
    /** @var ParamServiceInterface */
    private $paramService;
    /** @var BookingOrderManager */
    private $bookingOrderManager;
    /** @var DatComparator  */
    private $datComparator ;
    /** @var BookingOrder */
    private $bookingOrder;

    /** @var int */
    private $maxVisitors ;
    /** @var int */
    private $availableVisitorNumber;

    public function __construct(
        ParamServiceInterface $paramService,
        BookingOrderManager $bookingOrderManager,
        DatComparator $datComparator )
    {
        $this->paramService = $paramService;
        $this->datComparator = $datComparator;
        $this->bookingOrderManager = $bookingOrderManager;
    }

    public function validate($bookingOrder, Constraint $constraint)
    {    

        if (!$constraint instanceof BookingDisponibility)
        {
            throw new UnexpectedTypeException($constraint,  BookingDisponibility::class);

        }
        if (!is_object( $bookingOrder)) {
            // throw this exception if your validator cannot handle the passed type so that it can be marked as invalid
            throw new UnexpectedValueException($bookingOrder, 'objet');
        }
   
        $this->findAvailableVisitorNumber($bookingOrder);  
        
        if( ($this->availableVisitorNumber < $bookingOrder->getWishes()) || 
        ($this->availableVisitorNumber < count($bookingOrder->getVisitors())) ):
            $this->context->buildViolation($constraint->msgBookingExedesCapacity)
            ->setParameter('{{ available }}',$this->availableVisitorNumber)
            ->addViolation(); 
        endif;        
        
        $visitors = $bookingOrder->getVisitors();
        if(is_countable($visitors) ):
            $x = count($visitors);
        else:
            $x = 0;
        endif;

        if ( $x == 0 && $bookingOrder->getWishes() <= 0)
        {
            $this->context->buildViolation($constraint->msgBookingAddVisitors)
            ->setParameter('{{ enter your list of visitor }}',  "")
            ->addViolation(); 
        } 
               /*
        else{
         if ( $x == 1 && $bookingOrder->getTotalAmount() == 0):
                $this->context->buildViolation($constraint->msgBookingAddPayingVisitors)
            ->setParameter('{{ add a paid entry to free visit for underage kid  }}',  "")
            ->addViolation();
            endif;

        }*/
        
        
    }

    public function findAvailableVisitorNumber($bookingOrder)
    {
    
        $this->maxVisitors = $this->paramService->findMaxVisitors();

        $tstDate = $this->datComparator->convert($bookingOrder->getExpectedDate());
        $tstMonth = substr($tstDate , 0, 6);
        $tstExe = substr($tstDate , 0, 4);

    
        $this->maxVisitor = 0;

        foreach($this->maxVisitors as $param){
            $refDat = rtrim($param->getExenum(). $param->getMonthNum(). $param->getDayNum());

            if ($this->maxVisitor == 0 && $tstDate == $refDat) :
                $this->maxVisitor = $param->getNumber();
            endif;
            if ($this->maxVisitor == 0 && $tstMonth == $refDat ):
                $this->maxVisitor = $param->getNumber();
            endif;
            if ($this->maxVisitor == 0 && $tstExe == $refDat ):
                $this->maxVisitor = $param->getNumber();
            endif;

        }

        $bookedVisitors = $this->bookingOrderManager->findGlobalVisitorCount($bookingOrder);
        if( !is_integer($bookedVisitors)) : 
            $bookedVisitors = 0;
        endif;
        
        $this->availableVisitorNumber =  $this->maxVisitor - $bookedVisitors;
    
    }

}