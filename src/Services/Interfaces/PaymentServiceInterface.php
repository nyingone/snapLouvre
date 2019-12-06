<?php


namespace App\Services\Interfaces;


use Symfony\Component\Routing\RouterInterface;

interface PaymentServiceInterface
{
    public function reconcilePayment(string $sessionId) : bool;
}