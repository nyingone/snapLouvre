<?php

namespace App\Manager;

use App\Entity\BookingOrder;
use App\Event\Booking\BookingPlacedEvent;
use App\Event\Booking\BookingSettledEvent;
use App\Manager\Interfaces\BookingOrderManagerInterface;
use App\Repository\Interfaces\BookingOrderRepositoryInterface;
use App\Services\Interfaces\ParamServiceInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BookingOrderManager implements BookingOrderManagerInterface
{

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
     * @var SessionInterface
     */
    private $session;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;


    /**
     * BookingOrderManager constructor.
     * @param SessionInterface $session
     * @param EventDispatcherInterface $eventDispatcher
     * @param BookingOrderRepositoryInterface $bookingOrderRepository
     * @param VisitorManager $visitorManager
     * @param ParamServiceInterface $paramService
     * @param ValidatorInterface $validator
     * @throws \Exception
     */
    public function __construct(
        SessionInterface $session,
        EventDispatcherInterface $eventDispatcher,
        BookingOrderRepositoryInterface $bookingOrderRepository,
        VisitorManager $visitorManager,
        ParamServiceInterface $paramService,
        ValidatorInterface $validator)
    {
        $this->session = $session;
        $this->eventDispatcher = $eventDispatcher;
        $this->bookingOrderRepository = $bookingOrderRepository;
        $this->visitorManager = $visitorManager;
        $this->paramService = $paramService;
        $this->validator = $validator;

        $this->bookingOrderStartDate = new \DateTime('now');
        $this->bookingRef = 'TempRef' . $session->getId();
    }

    /**
     * @inheritDoc
     */
    public function razBookingOrder()
    {
        $this->session->invalidate();
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
        $amount = 0;

        $visitors = $bookingOrder->getVisitors();
        for ($i = count($visitors); $i < $bookingOrder->getWishes(); ++$i) {
            $visitor = $this->visitorManager->inzVisitor();
            $bookingOrder->addVisitor($visitor);
        }

        foreach ($visitors as $visitor) {
            $visitor = $this->visitorManager->refreshVisitor($visitor);
            $amount += $visitor->getCost();
        }

        $bookingOrder->setTotalAmount($amount);

        $this->setBookingOrder($bookingOrder);
    }


    public function bookingOrderControl()
    {
        //    TODO CLEAN    $errors = $this->validator->validate($this->bookingOrder, null, ['pre_booking']);
    }

    /** @inheritDoc */
    public function place(BookingOrder $bookingOrder)
    {
        $bookingOrder->setBookingRef($this->paramService->allocateBookingReference());
        $bookingOrder->setValidatedAt(new \DateTime('now'));

        $ticketOrder = 0;
        foreach ($bookingOrder->getVisitors() as $visitor) {
            $ticketOrder = $this->visitorManager->validVisitor($visitor, $ticketOrder);
        }

        $this->bookingOrderRepository->save($bookingOrder);

        $this->setBookingOrder($bookingOrder);

        $event = new BookingPlacedEvent($bookingOrder);
        // TODO DELETE PLACED EVENT ? creates the BookingPlacedEvent and dispatches it
        $this->eventDispatcher->dispatch($event);
    }


    public function reconcilePayment($bookingInfos = [], $paymentInfos = []): BookingOrder
    {
        $bookingOrder = $this->getBookingOrder();
        if ($bookingOrder->getBookingRef() == $bookingInfos['bookingReference'] && $bookingOrder->getSettledAt() == null) {

            $bookingOrder->setExtPaymentRef($paymentInfos['payment_ref']);
            $bookingOrder->setExtPaymentIntentRef($paymentInfos['payment_Intent']);
            $bookingOrder->setExtPaymentStatus($paymentInfos['status']);
            $bookingOrder->setSettledAt(new \DateTime('now'));

            $bookingOrder->getCustomer()->setLastName($paymentInfos['customer']);

            $bookingOrder = $this->bookingOrderRepository->save($bookingOrder);

            $event = new BookingSettledEvent($bookingOrder);
            // Dispatches mail
            $this->eventDispatcher->dispatch($event);

        }
        $this->setBookingOrder($bookingOrder);
        $this->bookingOrderRepository->save($bookingOrder);

        return $this->getBookingOrder();
    }

    public function confirmOrderSent(BookingOrder $bookingOrder)
    {
        $bookingOrder->setConfirmedAt(new \DateTime('now'));
        $this->bookingOrderRepository->save($bookingOrder);
    }


    /** @inheritDoc */
    public function remove(BookingOrder $bookingOrder): bool
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

    /**
     * @return mixed
     */
    public function getBookingOrder(): ?BookingOrder
    {
        if ($this->session->has('BookingOrder')) {
            $bookingOrder = $this->session->get('BookingOrder');

            if ($bookingOrder instanceOf BookingOrder && $bookingOrder->getId()) {
                return $this->bookingOrderRepository->find($bookingOrder);
            }
            return $bookingOrder;
        }

        return $this->inzBookingOrder();

    }

    public function clearVisitors(BookingOrder $bookingOrder)
    {
     $bookingOrder->clearVisitors();
     $this->setBookingOrder($bookingOrder);
    }

    /**
     * @param $bookingOrder
     */
    public function setBookingOrder($bookingOrder)
    {
        $this->session->set('BookingOrder', $bookingOrder);

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

    public function hasMultiRegisteredVisitor(BookingOrder $bookingOrder): bool
    {
        $visitors = $bookingOrder->getVisitors();
        foreach ($visitors as $visitor) {
            $i = 0;
            foreach ($bookingOrder->getVisitors() as $visitorToControl) {
                if ($visitor == $visitorToControl) {
                    $i++;
                    if ($i > 1): return true;
                    endif;
                }
            }
        }
        return false;
    }


}