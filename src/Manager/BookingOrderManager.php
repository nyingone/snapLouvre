<?php

namespace App\Manager;

use App\Entity\BookingOrder;
use App\Repository\Interfaces\BookingOrderRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingOrderManager
{
    /** @var SessionManager */
    private $sessionManager;

    /** @var BookingOrderRepositoryInterface */
    private $bookingOrderRepository;

    /** @var BookingOrder */
    private $bookingOrder;

    /** @var VisitorManager */
    protected $visitorManager;

    /** @var ValidatorInterface */
    protected $validator;

    protected $bookingRef;
    protected $bookingOrderStartDate;

    /** @var int */
    protected $amount;

    /**
     * @param SessionManager $sessionManager
     * @param BookingOrderRepositoryInterface $bookingOrderRepository
     * @param VisitorManager $visitorManager
     * @param ValidatorInterface $validator
     * @throws \Exception
     */
    public function __construct(
        SessionManager $sessionManager,
        BookingOrderRepositoryInterface $bookingOrderRepository,
        VisitorManager $visitorManager,
        ValidatorInterface $validator)
    {
        $this->sessionManager = $sessionManager;
        $this->bookingOrderRepository = $bookingOrderRepository;
        $this->visitorManager = $visitorManager;
        $this->validator = $validator;

        $this->bookingOrderStartDate = new \DateTime('now');
        $this->bookingRef = 'testTempRef' . $this->sessionManager->getProvisionalRef();

    }

    /** @param void
     * @return BookingOrder
     */
    public function inzBookingOrder(): BookingOrder
    {
        $this->bookingOrder = new BookingOrder();
        $this->bookingOrder->setOrderDate($this->bookingOrderStartDate);
        $this->bookingOrder->setBookingRef($this->bookingRef);

        return $this->bookingOrder;
    }

    /**
     * @param BookingOrder $bookingOrder
     * @return BookingOrder
     */
    public function refreshBookingOrder(BookingOrder $bookingOrder): BookingOrder
    {
        $this->bookingOrder = $bookingOrder;
        $amount = 0;

        $visitors = $this->bookingOrder->getVisitors();
        for ($i = count($visitors); $i <= $this->bookingOrder->getWishes(); ++$i) {
            $this->addVisitor();
        }

        foreach ($visitors as $visitor) {
            $visitor = $this->visitorManager->refreshVisitor($visitor);
            $amount += $visitor->getCost();
        }


        $this->bookingOrder->setTotalAmount($amount);
        //  $this->bookingOrderControl();

        //  $this->bookingOrderRepository->save($this->bookingOrder);

        return $this->bookingOrder;
    }

    private function addVisitor()
    {
        $visitor = $this->visitorManager->inzVisitor();
        $this->bookingOrder->addVisitor($visitor);
    }

    public function bookingOrderControl()
    {
        $errors = $this->validator->validate($this->bookingOrder, null, ['pre_booking']);

    }


    public function findGlobalVisitorCount(BookingOrder $bookingOrder)
    {
        return $this->bookingOrderRepository->findDaysEntriesFromTo($bookingOrder->getExpectedDate(), $bookingOrder->getExpectedDate());
    }

    public function getBookingOrder()
    {
        return $this->sessionManager->getBookingOrder();
    }

    public function hasNoPayingVisitor($bookingOrder) : bool
    {
        if ($bookingOrder->getTotalAmount() == 0 && count($bookingOrder->getVisitors() > 0)) {
            return true;
        }
    }


}