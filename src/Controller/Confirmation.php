<?php

namespace App\Controller;


use App\Controller\Interfaces\PaymentAuthenticate;
use App\Exception\UnIdentifiedPaymentException;
use App\Manager\BookingOrderManager;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Confirmation extends AbstractController implements PaymentAuthenticate
{

    /**
     * @Route("/confirmation{id<\/\w+>?}", name="confirmation")
     * @param SessionInterface $session
     * @param BookingOrderManager $bookingOrderManager
     * @param PaymentServiceInterface $paymentService
     * @return Response
     * @throws UnIdentifiedPaymentException
     */
    public function invoke(
        SessionInterface $session,
        BookingOrderManager $bookingOrderManager,
        PaymentServiceInterface $paymentService
    ): Response
    {
        if (!isset($_GET['sessionId']) || $_GET['sessionId'] !== $session->get('paymentSessionId')) {
            throw new UnIdentifiedPaymentException('This is not a valid Payment Identifier');
        } else {
            $endFlag = $paymentService->reconcilePayment(htmlentities($_GET['sessionId'], ENT_QUOTES,"UTF-8"));
        }
        if($endFlag) {
            $this->addFlash('success', 'Order paid!');
        } else{
            $this->addFlash('success', 'Order could not be completed!');
        }
        $bookingOrder = $bookingOrderManager->getBookingOrder();

        return $this->render('confirmation.html.twig', ['bookingOrder' => $bookingOrder,
        ]);
    }
}