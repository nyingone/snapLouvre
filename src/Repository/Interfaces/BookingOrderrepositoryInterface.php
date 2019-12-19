<?php

namespace App\Repository\Interfaces;

use App\Entity\BookingOrder;
use phpDocumentor\Reflection\DocBlock\Tags\Link;


interface BookingOrderRepositoryInterface
{
    public function find(BookingOrder $bookingOrder);
    public function save(BookingOrder $bookingOrder);
    public function remove(BookingOrder $bookingOrder);



}