<?php

namespace App\Manager;

use App\Entity\Visitor;
use App\Manager\Interfaces\VisitorManagerInterface;
use App\Services\Interfaces\PricingServiceInterface;
use App\Services\PricingService;
use App\Repository\Interfaces\VisitorRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class VisitorManager implements VisitorManagerInterface
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
            $visitor->setCost($this->pricingService->findVisitorTariff(
                $visitor->getBookingOrder()->getOrderDate(),
                $visitor->getBookingOrder()->getPartTimeCode(),
                $visitor->getDiscounted(),
                $visitor->getBirthDate()
            ));
        }

       // $this->visitorControl($visitor);

        return $visitor;
    }

    public function removeVisitor($visitor): void
    {
        $this->visitorRepository->remove($visitor);
    }



    public function visitorControl($visitor)
    {
        // $this->visitorRepository->save($visitor);

       //  $errors = $this->validator->validate($visitor);

    }


    /**
     * @inheritDoc
     */
    public function isMultiRegisteredVisitor(Visitor $visitorToControl) : bool
    {
        $i = 0;
        $visitors = $visitorToControl->getBookingOrder()->getVisitors();
        foreach ($visitors as $visitor) {
            if($visitor == $visitorToControl){
                $i++;
                if ($i > 1): return true;
                endif;
            }
        }

        return false;
    }

    public function isUnaccompaniedUnderage(Visitor $visitor): bool
    {
        if (count($visitor->getBookingOrder()->getVisitors()) == 1) {
            return true;
        }

        return false;
    }


}