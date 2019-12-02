<?php


namespace App\Repository\Interfaces;


use App\Entity\Customer;

interface CustomerRepositoryInterface
{
    /**
     * @param Customer $customer
     * @return Customer|null
     */
    public function find(Customer $customer): ?Customer;

    /**
     * @param Customer $customer
     * @return bool
     */

}