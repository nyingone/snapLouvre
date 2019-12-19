<?php


namespace App\EventSubscriber;


use App\Event\Booking\BookingPlacedEvent;
use App\Event\Booking\BookingSettledEvent;
use App\Manager\Interfaces\BookingOrderManagerInterface;
use App\Services\EmailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class BookingSubscriber implements EventSubscriberInterface
{

    /**
     * @var BookingOrderManagerInterface
     */
    private $bookingOrderManager;
    /**
     * @var EmailService
     */
    private $emailService;

    public function __construct(BookingOrderManagerInterface $bookingOrderManager, EmailService $emailService)
    {
        $this->bookingOrderManager = $bookingOrderManager;
        $this->emailService = $emailService;
    }


    public static function getSubscribedEvents()
    {
        return [
               BookingSettledEvent::class => 'onBookingSettled',
        ];
    }


    /**
     * @param BookingSettledEvent $event
     */
    public function onBookingSettled(BookingSettledEvent $event)
    {

        $response = 0;

        try {
            $response = $this->emailService->sendConfirmation($event->getBookingOrder());
        } catch (LoaderError $e) {
        } catch (RuntimeError $e) {
        } catch (SyntaxError $e) {
        }

        if ($response == 1) {
            $this->bookingOrderManager->confirmOrderSent($event->getBookingOrder());
        //    $this->emailService->unlinkQrCode($event->getBookingOrder());

        }
        // TODO TODO get OK/NOK Response

    }
}