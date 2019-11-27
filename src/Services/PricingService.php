<?php

namespace App\Services;

use App\Entity\Pricing;
use App\Services\Interfaces\ParamServiceInterface;
use App\Services\Interfaces\PricingServiceInterface;
use App\Repository\Interfaces\PricingRepositoryInterface;

class PricingService implements PricingServiceInterface
{
    /** @var PricingServiceInterface */
    private $pricingRepository;
    private $pricings = [];

    /** @var ParamServiceInterface */
    private $paramService;

    private $tariffDate;
    private $cost = null;

 
    /** @inheritDoc */
    public function __construct(PricingRepositoryInterface $pricingRepository, ParamServiceInterface $paramService)
    {
        $this->pricingRepository = $pricingRepository;
        $this->paramService= $paramService;
    }


    public function findLastTariffDate($date = null)
    {
        if($date == null):
            $date = new \datetime();
        endif;

        $tariffDates = $this->pricingRepository->findLastTariffDate($date);
        $tariffDate  = $tariffDates[0];

        foreach($tariffDate as $item => $date):
            $this->tariffDate = new \Datetime($date);
        endforeach;
    }
/**
 * get one or a group of terms for a tarif
 *
 * @param [date] $date
 * @param [int] $partTimeCode
 * @param [boolean] $discounted
 * @param [int] $age
 * @return [mixed] $cost
 */
    public function findVisitorTariff($date , $partTimeCode, $discounted, $age)
    {
        $this->findLastTariffDate($date);

    //    fetch pricing partTimeCode is null or = 
        
        $this->pricings = $this->pricingRepository->findLastPricing($this->tariffDate, $partTimeCode, $discounted, $age);
     
        foreach ($this->pricings as $pricing)
        { 
            if( ($age >= $pricing->getAgeMin() && $age < $pricing->getAgeMax()) &&
                ($pricing->getPartTimeCode() == null || $partTimeCode = $pricing->getPartTimeCode()) )
            {
                $this->cost = $pricing->getPrice();
                if( ($pricing->getPartTimeCode() == null) && ($partTimeCode !==0) ):               
                    $this->cost = $this->cost / $partTimeCode;
                endif;
            }  
        }
        return $this->cost;
    }

}