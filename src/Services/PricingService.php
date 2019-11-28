<?php

namespace App\Services;

use App\Services\Interfaces\ParamServiceInterface;
use App\Services\Interfaces\PricingServiceInterface;
use App\Repository\Interfaces\PricingRepositoryInterface;
use DateTime;

class PricingService implements PricingServiceInterface
{
    /** @var PricingServiceInterface */
    private $pricingRepository;
    private $pricings = [];

    /** @var ParamServiceInterface */
    private $paramService;

    private $tariffDate;
    private $cost = null;


    /**
     * @param PricingRepositoryInterface $pricingRepository
     * @param ParamServiceInterface $paramService
     */
    public function __construct(PricingRepositoryInterface $pricingRepository, ParamServiceInterface $paramService)
    {
        $this->pricingRepository = $pricingRepository;
        $this->paramService= $paramService;
    }

    /**
     * @param \DateTimeInterface $date
     * @throws \Exception
     */
   public function findLastTariffDate(\DateTimeInterface $date)
    {
        $tariffDates = $this->pricingRepository->findLastTariffDate($date);
        $tariffDate  = $tariffDates[0];

        foreach($tariffDate as $item => $date){
            $this->tariffDate = new \Datetime($date);
            return;
        }

    }

    /**
     * get one or a group of terms for a tarif
     *
     * @inheritDoc
     * @throws \Exception
     */
    public function findVisitorTariff(\DateTimeInterface $date , $partTimeCode, $discounted, \DateTimeInterface $birthDate) : int
    {
        $this->findLastTariffDate($date);

    //    fetch pricing partTimeCode is null or = 
        $age = $birthDate->diff(new DateTime())->y;
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