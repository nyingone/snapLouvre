<?php


namespace App\Services;


use App\Entity\BookingOrder;
use App\Manager\BookingOrderManager;
use App\Services\Interfaces\PaymentServiceInterface;
use Stripe\Checkout\Session as StripeSession;
use Stripe\PaymentIntent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class PaymentService implements PaymentServiceInterface
{
    /** @var SessionInterface */
    private $session;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var string
     */
    private $stripePublicKey;
    /**
     * @var string
     */
    private $stripeSecretKey;
    /**
     * @var BookingOrderManager
     */
    private $bookingOrderManager;

    /**
     * PaymentService constructor.
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param BookingOrderManager $bookingOrderManager
     * @param string $stripePublicKey
     * @param string $stripeSecretKey
     */
    public function __construct(
        RouterInterface $router,
        SessionInterface $session,
        BookingOrderManager $bookingOrderManager,
        string $stripePublicKey,
        string $stripeSecretKey)
    {
        $this->router = $router;
        $this->session = $session;
        $this->bookingOrderManager = $bookingOrderManager;
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeSecretKey = $stripeSecretKey;

    }

    public function setCheckoutSession(
        BookingOrder $bookingOrder,
        string $redirectOK,
        string $redirectNOK): StripeSession

    {
        $items = $this->preparePayment($bookingOrder);
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);

        $stripeSession = \Stripe\Checkout\Session::create([
            'client_reference_id' => $bookingOrder->getBookingRef(),
            'customer_email' => $bookingOrder->getCustomer()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => $items,
            'success_url' => $this->router->generate($redirectOK, [], RouterInterface::ABSOLUTE_URL) . '?sessionId={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->router->generate($redirectNOK, [], RouterInterface::ABSOLUTE_URL),
        ]);


        $this->session->set('paymentSessionId', $stripeSession->id);
        $this->session->set('stripePaymentIntent', $stripeSession->payment_intent);
        return $stripeSession;
    }

    public function getPublicKey(): string
    {
        return $this->stripePublicKey;
    }


    private function preparePayment(BookingOrder $bookingOrder): array
    {
        $items = [];
        foreach ($bookingOrder->getVisitors() as $visitor) {
            $items[] = [
                'name' => 'Visitor' . '/' . $visitor->getName(),
                'description' => $visitor->getName(),
                'amount' => $visitor->getCost(),
                'currency' => 'eur',
                'quantity' => 1,
            ];
        }
        return $items;
    }


    public function reconcilePayment(string $paymentSessionId): BookingOrder
    {
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
        $sessionInfos = StripeSession::retrieve($paymentSessionId);
        $bookingInfos = [
            'bookingReference' => $sessionInfos->client_reference_id
        ];

        $paymentInfos = PaymentIntent::retrieve($this->session->get('stripePaymentIntent'));

        $payingInfos = [
            'customer' => $paymentInfos->customer,
            'payment_Intent' => $paymentInfos->id,
            'payment_ref' => $paymentInfos->payment_method,
            'status' => $paymentInfos->status,
            'amount_received' => $paymentInfos->amount_received
        ];

        return $this->bookingOrderManager->reconcilePayment($bookingInfos, $payingInfos);

    }

}