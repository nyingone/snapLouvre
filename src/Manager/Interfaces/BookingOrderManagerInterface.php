<?php

namespace App\Manager\Interfaces;

use App\Entity\BookingOrder;

interface BookingOrderManagerInterface
{

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


    /**
     * @param BookingOrder $bookingOrder
     * @return bool
     */
    public function place(BookingOrder $bookingOrder);

    /**
     * @param BookingOrder $bookingOrder
     * @return bool
     */
    public function remove(BookingOrder $bookingOrder);
}

