<?php

namespace App\Controller;


use App\Controller\Interfaces\PaymentAuthenticate;
use App\Entity\BookingOrder;
use App\Exception\UnIdentifiedPaymentException;
use App\Manager\BookingOrderManager;
use App\Services\EmailService;
use App\Services\Interfaces\PaymentServiceInterface;
use App\Services\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class Confirmation extends AbstractController
{

    /**
     * @Route("/confirmation{id<\/\w+>?}", name="confirmation")
     * @param Request $request
     * @param SessionInterface $session
     * @param BookingOrderManager $bookingOrderManager
     * @param QrCodeService $qrCodeService
     * @param PaymentServiceInterface $paymentService
     * @return Response
     */
    public function invoke(
        Request $request,
        SessionInterface $session,
        BookingOrderManager $bookingOrderManager,
        QrCodeService $qrCodeService,
        PaymentServiceInterface $paymentService
    ): Response
    {
        $step = $bookingOrderManager->ControlBooking('step4', $request);


        if ($step == "step1") {
            return $this->redirectToRoute('home');
        }

        if ($step == "step5") {
            $stripeSession = $request->query->get('sessionId');

            $bookingOrder = $paymentService->reconcilePayment($stripeSession);
        }
        $bookingOrder =$bookingOrderManager->getBookingOrder();

        return $this->render('confirmation.html.twig', ['bookingOrder' => $bookingOrder,
            'qrCode' => $qrCodeService->genQrCode($bookingOrder->getBookingRef()),
        ]);

    }
}