<?php


namespace App\Manager;



use App\Entity\Customer;
use App\Repository\Interfaces\CustomerRepositoryInterface;
use App\Services\Interfaces\ParamServiceInterface;


class CustomerManager
{
    /**
     * @var SessionManager
     */
    private $sessionManager;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var BookingOrderManager
     */
    private $bookingOrderManager;
    /**
     * @var ParamServiceInterface
     */
    private $paramService;


    /**
     * CustomerManager constructor.
     * @param SessionManager $sessionManager
     * @param CustomerRepositoryInterface $customerRepository
     * @param BookingOrderManager $bookingOrderManager
     * @param ParamServiceInterface $paramService
     * @throws \Exception
     */
    public function __construct(
        SessionManager $sessionManager,
        CustomerRepositoryInterface $customerRepository,
        BookingOrderManager $bookingOrderManager,
        ParamServiceInterface $paramService)
    {
        $this->sessionManager = $sessionManager;
        $this->customerRepository = $customerRepository;
        $this->bookingOrderManager = $bookingOrderManager;
        $this->paramService = $paramService;
    }


    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->sessionManager->sessionGet('customer');
    }

    /**
     * @param $customer
     */
    public function setCustomer($customer):void
    {
        $this->sessionManager->sessionSet('customer', $customer);
    }
}