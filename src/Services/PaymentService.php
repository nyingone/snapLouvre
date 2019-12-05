<?php


namespace App\Services;


use App\Entity\BookingOrder;
use App\Services\Interfaces\PaymentServiceInterface;
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
     * PaymentService constructor.
     * @param RouterInterface $router
     * @param SessionInterface $session
     * @param string $stripePublicKey
     * @param string $stripeSecretKey
     */
    public function __construct(RouterInterface $router, SessionInterface $session, string $stripePublicKey, string $stripeSecretKey)
    {
        $this->router = $router;
        $this->stripePublicKey = $stripePublicKey;
        $this->stripeSecretKey = $stripeSecretKey;
        $this->session = $session;
    }

    public function setCheckoutSession(BookingOrder $bookingOrder, string $redirectOK, string $redirectNOK)
    {
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);

        $stripeSession = \Stripe\Checkout\Session::create([
            'customer_email' => $bookingOrder->getCustomer()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [[
                'name' => 'SnapLouvre tickets',
                'description' => $bookingOrder->getBookingRef(),
                'amount' => $bookingOrder->getTotalAmount(),
                'currency' => 'eur',
                'quantity' => 1,
            ]],
            'success_url' => $this->router->generate($redirectOK,[], RouterInterface::ABSOLUTE_URL).'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $this->router->generate($redirectNOK,[], RouterInterface::ABSOLUTE_URL),
        ]);

        $this->session->set('stripeSession', $stripeSession);
        return $stripeSession;
    }

    public function getPublicKey()
    {
        return $this->stripePublicKey;
    }

    public function getSessionId()
    {
        $stripeSession = $this->session->get('stripeSession');
        // TODO $sessionId = $session->getRequest()->query->get('sessionId');
        dump($stripeSession->id);
        return $stripeSession->id;
    }

    public function getPaymentIntent($sessionId)
    {
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);

        $stripeResponse = \Stripe\PaymentIntent::retrieve(
            $sessionId
        );
    }
}