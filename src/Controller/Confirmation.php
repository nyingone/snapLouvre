<?php

namespace App\Controller;


use App\Manager\BookingOrderManager;
use App\Manager\SessionManager;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Confirmation extends AbstractController
{
    /**
     * @var BookingOrderManager
     */
    private $bookingOrderManager;

    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * Confirmation constructor.
     * @param BookingOrderManager $bookingOrderManager
     * @param PaymentService $paymentService
     */
    public function __construct(BookingOrderManager $bookingOrderManager, PaymentService $paymentService)
    {
        $this->bookingOrderManager = $bookingOrderManager;
        $this->paymentService = $paymentService;
    }

    /**
     * @Route("/confirmation", name="confirmation")
     * confirmation?session_id=cs_test_HCOAwmCDI3dnqthEf6oxW2AR0BPIUYT656m6OouJ6LPzV0RKS2Tcb8Sj
     * @param string $sessionId
     * @return Response
     */
    public function index(string $sessionId) : Response
    {
        dump($this->paymentService->getPaymentIntent($sessionId));

        $bookingOrder = $this->bookingOrderManager->getBookingOrder();
        return $this->render('confirmation.html.twig', ['bookingOrder' => $bookingOrder,
        ]);
    }
}