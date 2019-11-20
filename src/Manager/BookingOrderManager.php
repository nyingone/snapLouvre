<?php

namespace App\Manager;

use App\Entity\BookingOrder;
use App\Repository\Interfaces\BookingOrderRepositoryInterface;

class BookingOrderManager extends BookingDefaultManager
{
    /** @var BookingOrderRepositoryInterface  */
    private $bookingOrderRepository;

    /** @var BookingOrder */
    private $bookingOrder;

    /** @var VisitorManagerInterface  */
    protected $visitorManager;

    protected $bookingRef;
    protected $bookingOrderStartDate;


    public function __construct(BookingOrderRepositoryInterface $bookingOrderRepository, VisitorManager $visitorManager)
    {
        parent:: __construct;

        $this->bookingOrderRepository = $bookingOrderRepository;
        $this->visitorManager = $visitorManager;
        $this->bookingOrderStartDate = new \DateTime('now');
    }

    /** @param void
     * @return BookingOrder
     */
    public function inzBookingOrder(): object
    {
        $this->bookingOrder= new BookingOrder();
        $this->bookingOrder->setOrderDate( $this->bookingOrderStartDate);

        return $this->bookingOrder;
    }


    public function refreshBookingOrder($bookingOrder)
    {
        $this->error_list = [];
        if ($this->bookingRef == null)
        {
            $this->bookingRef = $this->session->get('_csrf/customer');
        }

        $bookingOrder->setBookingRef($this->bookingRef);
        $amount = 0;

        $visitors = $bookingOrder->getVisitors();

        if (count($visitors) == 0)
        {
            for ($i = 1;  $i <= $bookingOrder->getWishes(); ++$i)
            {
                $this->addVisitor($bookingOrder);
            }

        } else {

            foreach($visitors as $visitor){
                $this->visitor = $this->visitorAuxiliary->refreshVisitor($visitor);
                $amount += $this->visitor->getCost();
            }

        }

        $bookingOrder->setTotalAmount($amount);

        $this->bookingOrderRepository->save($bookingOrder);
        $this->bookingOrderControl($bookingOrder);
        $this->session->set('bookingOrder_error', $this->error_list);

        return $bookingOrder;
    }

    public function addVisitor($bookingOrder)
    {
        $visitor = $this->visitorAuxiliary->inzVisitor();
        $bookingOrder->addVisitor($visitor);
    }

    public function bookingOrderControl($bookingOrder)
    {
        $errors = $this->validator->validate($bookingOrder);
        if (count($errors) > 0)
        {
            $this->error_list[] = (string) $errors;
        }
    }

    public function findOrders()
    {
        return  count($bookingOrders = $this->bookingOrderRepository->findAll());
    }

    public function findGlobalVisitorCount(BookingOrder $bookingOrder): array
    {

        return $this->bookingOrderRepository->findDaysEntriesFromTo($bookingOrder->getExpectedDate(), $bookingOrder->getExpectedDate());

    }


}