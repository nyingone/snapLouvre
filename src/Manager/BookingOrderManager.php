<?php

namespace App\Manager;

use App\Entity\BookingOrder;
use App\Manager\Interfaces\BookingOrderManagerInterface;
use App\Repository\Interfaces\BookingOrderRepositoryInterface;
use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingOrderManager implements BookingOrderManagerInterface
{
    /** @var SessionManager */
    private $sessionManager;

    /** @var BookingOrderRepositoryInterface */
    private $bookingOrderRepository;

    /** @var ParamServiceInterface */
    private $paramService;

    /** @var BookingOrder */
    private $bookingOrder;

    /** @var VisitorManager */
    protected $visitorManager;

    /** @var ValidatorInterface */
    protected $validator;

    protected $bookingRef;
    protected $orderDate;
    protected $bookingOrderStartDate;

    /** @var int */
    protected $amount;

    /**
     * @param SessionManager $sessionManager
     * @param BookingOrderRepositoryInterface $bookingOrderRepository
     * @param VisitorManager $visitorManager
     * @param ParamServiceInterface $paramService
     * @param ValidatorInterface $validator
     * @throws \Exception
     */
    public function __construct(
        SessionManager $sessionManager,
        BookingOrderRepositoryInterface $bookingOrderRepository,
        VisitorManager $visitorManager,
        ParamServiceInterface $paramService,
        ValidatorInterface $validator)
    {
        $this->sessionManager = $sessionManager;
        $this->bookingOrderRepository = $bookingOrderRepository;
        $this->visitorManager = $visitorManager;
        $this->paramService = $paramService;
        $this->validator = $validator;

        $this->bookingOrderStartDate = new \DateTime('now');
        $this->bookingRef = 'testTempRef' . $this->sessionManager->getProvisionalRef();

    }

    /**
     * @inheritDoc
     */
    public function inzBookingOrder(): BookingOrder
    {
        $this->bookingOrder = new BookingOrder();

        if (isset($orderDate)) {
            $this->bookingOrder->setOrderDate($orderDate);
        } else {
            $this->bookingOrder->setOrderDate($this->bookingOrderStartDate);
        }

        $this->bookingOrder->setBookingRef($this->bookingRef);

        $this->setBookingOrder($this->bookingOrder);

        return $this->bookingOrder;
    }

    /**
     * @param BookingOrder $bookingOrder
     * @return void
     * @throws \Exception
     */
    public function refreshBookingOrder(BookingOrder $bookingOrder): void
    {
        $this->bookingOrder = $bookingOrder;
        $amount = 0;

        $visitors = $this->bookingOrder->getVisitors();
        for ($i = count($visitors); $i < $this->bookingOrder->getWishes(); ++$i) {
            $this->addVisitor();
        }

        foreach ($visitors as $visitor) {
            $visitor = $this->visitorManager->refreshVisitor($visitor);
            $amount += $visitor->getCost();
        }


        $this->bookingOrder->setTotalAmount($amount);
        //  $this->bookingOrderControl();

        //  $this->bookingOrderRepository->save($this->bookingOrder);

        $this->setBookingOrder($this->bookingOrder);
    }

    private function addVisitor()
    {
        $visitor = $this->visitorManager->inzVisitor();
        $this->bookingOrder->addVisitor($visitor);
    }

    public function bookingOrderControl()
    {
        //    $errors = $this->validator->validate($this->bookingOrder, null, ['pre_booking']);
    }

    /** @inheritDoc */
    public function save(BookingOrder $bookingOrder)
    {
        $this->setBookingOrder($this->bookingOrderRepository->save($bookingOrder));
    }

    /** @inheritDoc */
    public function remove(BookingOrder $bookingOrder)
    {
        return $this->bookingOrderRepository->remove($bookingOrder);
    }

    /**
     * @param BookingOrder $bookingOrder
     * @return int
     */
    public function findDayVisitorCount(BookingOrder $bookingOrder): int
    {
        return count($this->bookingOrderRepository->findDaysEntriesFromTo($bookingOrder->getExpectedDate(), $bookingOrder->getExpectedDate()));
    }

    public function getBookingOrder()
    {
        $booking = $this->sessionManager->sessionGet('bookingOrder');

        if($booking->getId()){
            /// va chercher dans la bdd TODO
        }

        return $booking;
    }

    public function setBookingOrder($bookingOrder)
    {
        $this->sessionManager->sessionSet('bookingOrder', $bookingOrder);
    }

    /**
     * @inheritDoc
     */
    public function hasOnlyFreeVisitors(BookingOrder $bookingOrder): bool
    {
        If (count($bookingOrder->getVisitors()) > 1 && $bookingOrder->getTotalAmount() == 0) {
            return true;
        }
        return false;
    }

    /**
     * @param BookingOrder $bookingOrder
     * @return bool
     */
    public function cannotProvideEnoughTickets(BookingOrder $bookingOrder): bool
    {
        $maxEntries = $this->paramService->findMaxDayEntries($bookingOrder->getExpectedDate());
        $alreadyBooked = $this->findDayVisitorCount($bookingOrder);

        if ($bookingOrder->getWishes() > ($maxEntries - $alreadyBooked)) {
            return true;
        }

        return false;

    }

}