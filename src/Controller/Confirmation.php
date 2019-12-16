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
use Symfony\Contracts\Translation\TranslatorInterface;

class Confirmation extends AbstractController implements PaymentAuthenticate
{
    /** @var BookingOrder
     */
    private $bookingOrder;

    /**
     * @Route("/confirmation{id<\/\w+>?}", name="confirmation")
     * @param Request $request
     * @param TranslatorInterface $translator
     * @param SessionInterface $session
     * @param BookingOrderManager $bookingOrderManager
     * @param PaymentServiceInterface $paymentService
     * @return Response
     */
    public function invoke(
        Request $request,
        TranslatorInterface $translator,
        SessionInterface $session,
        BookingOrderManager $bookingOrderManager,
        PaymentServiceInterface $paymentService
    ): Response
    {
        $this->bookingOrder = $session->get('BookingOrder');

        if($this->bookingOrder->getSettledAt() == null) {
            $stripeSession = $request->query->get('sessionId');

            if ($stripeSession !== $session->get('paymentSessionId')) {
                // throw new UnIdentifiedPaymentException('This is not a valid Payment Identifier');
                $this->addFlash('warning', $translator->trans('Invalid_Payment_Identifier'));
            } else {
                $this->bookingOrder = $paymentService->reconcilePayment($stripeSession);
            }
        }

        $this->bookingOrder = $session->get('BookingOrder');


        if ($this->bookingOrder->getSettledAt() !== null) {
            return $this->render('confirmation.html.twig', ['bookingOrder' => $this->bookingOrder,
            ]);
        } else {
            $this->addFlash('warning', $translator->trans('Order_could_not_be_completed'));
            return $this->redirectToRoute('home');
        }

    }
}