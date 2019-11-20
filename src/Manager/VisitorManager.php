<?php

namespace App\Manager;

use App\Entity\Visitor;
use App\Services\PricingService;
use App\Repository\Interfaces\VisitorRepositoryInterface;

class VisitorManager extends BookingDefaultManager
{
    /** @var VisitorRepositoryInterface  */
    private $visitorRepository;
/** @var PricingService  */
    private $pricingService;


    public function __construct(VisitorRepositoryInterface $visitorRepository, PricingService $pricingService)
    {
        $this->visitorRepository = $visitorRepository;
        $this->pricingService = $pricingService;
        $this->validator = $validator;
        $this->session = $session;
    }


    public function sessionSet()
    {
        if (is_array($this->error_list) && !empty($this->error_list) )
        {
            $error_list0 = $this->session->get('customer_error');
            if ( !is_array($error_list0) || empty($error_list0) ):
                $error_list = $this->error_list;
            else:
                $error_list = array_merge($error_list0 , $this->error_list);
            endif;

            $this->session->set('booking_error', $error_list);
        }
    }

    public function inzVisitor(): object
    {
        $this->visitor = new Visitor;
        $this->visitor->setCountry('XX');

        $this->visitorControl($this->visitor);
        return $this->visitor;

    }

    public function refreshVisitor($visitor): object
    {

        $visitor->setCreatedAt($visitor->getBookingOrder()->getOrderDate());
        $visitor->setCost($this->pricingService->findVisitorTarif(
            $visitor->getBookingOrder()->getOrderDate(),
            $visitor->getBookingOrder()->getPartTimeCode(),
            $visitor->getDiscounted(),
            $visitor->getAgeYearsOld())) ;

        $this->visitorControl($visitor);

        return $visitor;
    }

    public function removeVisitor($visitor): void
    {
        $this->visitorRepository->remove($visitor);
    }



    public function visitorControl($visitor)
    {
        $this->visitorRepository->save($visitor);

        $errors = $this->validator->validate($visitor);

        if (count($errors) > 0)
        {
            $this->error_list[] = (string) $errors;
            $this->session->set('visitor_error', $this->error_list);
        }

    }


    public function controlKnownVisitor($visitor)
    {
        return ($this->visitorRepository->findGroupBy($visitor));
    }


}