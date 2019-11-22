<?php

namespace App\Manager;

use App\Entity\Visitor;
use App\Services\Interfaces\PricingServiceInterface;
use App\Services\PricingService;
use App\Repository\Interfaces\VisitorRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VisitorManager
{
    /** @var SessionManager */
    private  $sessionManager;
    /** @var VisitorRepositoryInterface  */
    private $visitorRepository;
    /** @var PricingService  */
    private $pricingService;
    /** @var ValidatorInterface */
    protected $validator;

    /**
     * @param SessionManager $sessionManager
     * @param VisitorRepositoryInterface $visitorRepository
     * @param PricingServiceInterface $pricingService
     * @param ValidatorInterface $validator
     */
    public function __construct(SessionManager $sessionManager, VisitorRepositoryInterface $visitorRepository, PricingServiceInterface $pricingService, ValidatorInterface $validator)
    {
        $this->sessionManager = $sessionManager;
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

    /** @param Visitor
     * @return Visitor
     */
    public function refreshVisitor(Visitor $visitor): object
    {
        $visitor->setCreatedAt($visitor->getBookingOrder()->getOrderDate());
        $visitor->setCost($this->pricingService->findVisitorTarif(
            $visitor->getBookingOrder()->getOrderDate(),
            $visitor->getBookingOrder()->getPartTimeCode(),
            $visitor->getDiscounted(),
            $visitor->getAgeYearsOld())) ;

       // $this->visitorControl($visitor);

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
           $error_list[] = (string) $errors;
           $this->sessionManager->sessionSet('visitor_error' . $visitor->getName(), $error_list);
        }

    }


    /* public function controlKnownVisitor($visitor)
    {
        return ($this->visitorRepository->findGroupBy($visitor));
    } */


}