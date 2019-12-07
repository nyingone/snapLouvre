<?php


namespace App\Services\Interfaces;


use App\Entity\BookingOrder;
use Symfony\Component\Routing\RouterInterface;

interface PaymentServiceInterface
{
    public function reconcilePayment(string $paymentSessionId) : BookingOrder;
}