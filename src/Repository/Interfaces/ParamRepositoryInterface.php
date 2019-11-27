<?php

namespace App\Repository\Interfaces;

interface ParamRepositoryInterface
{

   /** @return mixed */
   public function findAll();

    /**
     * @param $keyBookingOrderNumber
     * @return string
     */
    public function saveNumber($keyBookingOrderNumber): string;
}