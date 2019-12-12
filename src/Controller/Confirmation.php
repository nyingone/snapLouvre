<?php

namespace App\Controller;


use App\Controller\Interfaces\PaymentAuthenticate;
use App\Entity\BookingOrder;
use App\Exception\UnIdentifiedPaymentException;
use App\Manager\BookingOrderManager;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class Confirmation extends AbstractController implements PaymentAuthenticate
{

    /**
     * @Route("/confirmation{id<\/\w+>?}", name="confirmation")
     * @param Request $request
     * @param SessionInterface $session
     * @param BookingOrderManager $bookingOrderManager
     * @param PaymentServiceInterface $paymentService
     * @return Response
     */
    public function invoke(
        Request $request,
        SessionInterface $session,
        BookingOrderManager $bookingOrderManager,
        PaymentServiceInterface $paymentService
    ): Response
    {
        $stripeSession = $request->query->get('sessionId');

        if ($stripeSession !== $session->get('paymentSessionId')) {
           // throw new UnIdentifiedPaymentException('This is not a valid Payment Identifier');
            $this->addFlash('alert', 'Invalid Payment Identifier' );
        } else {
            $bookingOrder = $paymentService->reconcilePayment($stripeSession);
        }

        $session->remove('bookingOrder');

        if(isset($bookingOrder)) {
            return $this->render('confirmation.html.twig', ['bookingOrder' => $bookingOrder,
            ]);
        } else{
            $this->addFlash('success', 'Order could not be completed!');
            return $this->redirectToRoute('home');
        }

    }
}