<?php

namespace App\Manager;

use App\Entity\Visitor;
use App\Services\Interfaces\PricingServiceInterface;
use App\Services\PricingService;
use App\Repository\Interfaces\VisitorRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VisitorManager
{
    /** @var VisitorRepositoryInterface  */
    private $visitorRepository;
    /** @var PricingService  */
    private $pricingService;
    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @param VisitorRepositoryInterface $visitorRepository
     * @param PricingServiceInterface $pricingService
     * @param ValidatorInterface $validator
     */
    public function __construct( VisitorRepositoryInterface $visitorRepository, PricingServiceInterface $pricingService, ValidatorInterface $validator)
    {
        $this->visitorRepository = $visitorRepository;
        $this->pricingService = $pricingService;
        $this->validator = $validator;
    }

/**
 * @return Visitor
 */
    public function inzVisitor(): object
    {
        $visitor = new Visitor;
        $visitor->setCountry('XX');

       // $this->visitorControl($visitor);
        return $visitor;

    }

    /**
     * @param Visitor $visitor
     * @return object
     * @throws \Exception
     */
    public function refreshVisitor(Visitor $visitor): object
    {
        $visitor->setCreatedAt($visitor->getBookingOrder()->getOrderDate());
        $birthDate = $visitor->getBirthDate();
        if(isset($birthDate)) {
            $visitor->setTariff($this->pricingService->findVisitorTariff(
                $visitor->getBookingOrder()->getOrderDate(),
                $visitor->getBookingOrder()->getPartTimeCode(),
                $visitor->getDiscounted(),
                $visitor->getBirthDate()
            ));
        }


        return $visitor;
    }

    public function removeVisitor($visitor): void
    {
        $this->visitorRepository->remove($visitor);
    }



    public function validVisitor(Visitor $visitor, int $ticketOrder) : int
    {
        $ticketOrder ++;
        $visitor->setConfirmedAt($visitor->getBookingOrder()->getValidatedAt());
        $visitor->setTicketRef($visitor->getBookingOrder()->getBookingRef() . 'vis###_'. sprintf("%'.03d\n", $ticketOrder));
        return $ticketOrder;
    }


}