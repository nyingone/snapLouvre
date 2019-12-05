<?php


namespace App\Event\Booking;


use App\Entity\BookingOrder;
use Symfony\Contracts\EventDispatcher\Event;

class BookingSettledEvent extends Event
{
    public const NAME = 'booking.settled';

    protected $bookingOrder;

    public function __construct(BookingOrder $bookingOrder)
    {
        $this->bookingOrder = $bookingOrder;
    }


    public function getBookingOrder()
    {
        return $this->bookingOrder;
    }

}