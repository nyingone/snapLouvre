<?php


namespace App\EventSubscriber;


use App\Controller\Interfaces\PaymentAuthenticate;
use App\Services\PaymentService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class PaymentAuthenticateSubscriber implements EventSubscriberInterface
{
    /** @var  */
    private $session;

    /** @var  */
    private $paymentSessionId = null;

    /**
     * PaymentAuthenticateSubscriber constructor.
     * @param SessionInterface $session
     * @param PaymentService $paymentService
     */
    public function __construct(SessionInterface $session, PaymentService $paymentService)
    {
        if($session->has('paymentSession')) {
            $this->paymentSessionId = $session->get('paymentSession');
        }

    }

    public function onKernelController(ControllerEvent $event)
    {
        $action = $event->getController();

      /*  if ((!is_array($action)) || !$action[0] instanceOf PaymentAuthenticate) {
           return;
        } */

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        $sessionId = $event->getRequest()->query->get('sessionId');
        //  #requestUri: "/confirmation?session_id=cs_test_NuAKfk3yPrEnjh3XMt2tUO01do2xkfqhEB9qgUBu2XFswnn6YrpXcsXj"
        // dump($event);
        if ($sessionId !== $this->paymentSessionId) {
         //   throw new UnIdentifiedPaymentException('This is not a valid Payment Identifier');
            return;
        }

        // TODO RECUPDATA and SEND MAIL


    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}