<?php

namespace App\Manager\Interfaces;

use App\Entity\BookingOrder;

interface BookingOrderManagerInterface
{

    /**
     * @param \DateTimeInterface|null $orderDate
     * @param string|null $bookingRef
     * @return BookingOrder
     */
    public function inzBookingOrder(\DateTimeInterface $orderDate = null, string $bookingRef = null): BookingOrder;

    /**
     * @param BookingOrder $bookingOrder
     * @return bool
     */
    public function hasOnlyFreeVisitors(BookingOrder $bookingOrder): bool;

    /**
     * @param BookingOrder $bookingOrder
     * @return bool
     */
    public function cannotProvideEnoughTickets(BookingOrder $bookingOrder): bool;
}

