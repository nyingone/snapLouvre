<?php

namespace App\Services;


use App\Entity\Param;
use DateTime;
use App\Services\Interfaces\ParamServiceInterface;
use App\Repository\Interfaces\ParamRepositoryInterface;

class ParamService implements ParamServiceInterface
{
    /** @var ParamRepositoryInterface  */
    private $paramRepository;
    /** @var Param */
    private $param;

    private $partTimeCodes = [];
    private $partTimeArray = [];
    private $maxVisitors = [];
    private $imperativeEndOfBooking ;
    private $endOfBooking ;
    private $startOfBooking ;
    private $maxBookingVisitors;
    
    private const KBON = "BookingOrderNumber";

    /** @inherit */
    public function __construct(ParamRepositoryInterface $paramRepository)
    {
       $this->paramRepository = $paramRepository;
       $params = $this->paramRepository
       ->findAll();
       
        foreach($params as $param)
        {
            if($param->getRefCode() == "MaxVisitors")
            {
                $this->maxVisitors[] = $param;
            }

            if($param->getRefCode() == "MaxBookingOrderDly")
            {
                $nbMonths = $param->getNumber();
                $this->endOfBooking = new \DateTime('+'. $nbMonths . 'month');

                if($param->getDayNum() !== ''):
                    $this->endOfBooking  = new \DateTime($this->endOfBooking ->format('Y-m-t'));
                endif;
            } 

            if($param->getRefCode() == "ImperativeBookingEnd")
            {
                $date = $param->getExeNum() . "-" .  $param->getMonthNum() . "-" .  $param->getDayNum() ;
                $this->imperativeEndOfBooking = new \Datetime($date);
                
            } 

            if($param->getRefCode() == "PartTimeCodes")
            {
                $list = $param->getList();
                array_push($this->partTimeCodes, $list);
            } 

            if($param->getRefCode() == "PartTimeCode")
            {
                $this->partTimeArray[$param->getLabel() ] =  $param->getNumber();
            }

            if($param->getRefCode() == "MaxBookingVisitors")
            {
                $this->maxBookingVisitors =  $param->getNumber();
            } 
        
        }
    
    }
   
    /**
     * Undocumented function
     *
     * @return string
     */
    public function allocateBookingNumber():string
    {
        return $this->paramRepository->saveNumber(KBON);
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    public function findPartTimeCodes():array
    {
        return $this->partTimeCodes;
    }

       /**
     * Undocumented function
     *
     * @return array
     */
    public function findPartTimeArray():array
    {
        return $this->partTimeArray;
    }

    /**
     * Undocumented function
     *
     * @return datetime
     */
    public function findEndOfBooking():datetime
    {
        if($this->endOfBooking <= $this->imperativeEndOfBooking):
            return  $this->endOfBooking;
        else:
            return $this->imperativeEndOfBooking;
        endif;
    }

    /**
     * Undocumented function
     *
     * @return datetime
     */
    public function findStartOfBooking():datetime
    {
        $this->startOfBooking = new \datetime();
        return  $this->startOfBooking;
    }

    /**
     * Undocumented function
     *
     * @return DateTime
     */
    public function findImperativeEndOfBooking():DateTime
    {
        return $this->imperativeEndOfBooking;
    }
    /**
     * Find "MaxVisitors" per day explicited by Year, ou yearMonth, or day
     *
     * @return 
     */
    public function findMaxVisitors():array
    {
        return $this->maxVisitors;
    }

    /**
     * Undocumented function
     *
     * @return integer
     */
    public function findMaxBookingVisitors():int
    {
        return $this->maxBookingVisitors;
    }
    
    
}