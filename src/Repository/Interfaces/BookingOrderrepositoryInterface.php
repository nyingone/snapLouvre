<?php

namespace App\Repository\Interfaces;

use App\Entity\BookingOrder;


interface BookingOrderRepositoryInterface
{
    public function find(BookingOrder $bookingOrder);
    public function save(BookingOrder $bookingOrder);
    public function remove(BookingOrder $bookingOrder);

}