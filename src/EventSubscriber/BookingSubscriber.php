<?php


namespace App\EventSubscriber;


use App\Event\Booking\BookingPlacedEvent;
use App\Event\Booking\BookingSettledEvent;
use App\Manager\Interfaces\BookingOrderManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class BookingSubscriber implements EventSubscriberInterface
{

    /**
     * @var BookingOrderManagerInterface
     */
    private $bookingOrderManager;

    public function __construct(BookingOrderManagerInterface $bookingOrderManager)
    {
        $this->bookingOrderManager = $bookingOrderManager;
    //  dd('bookingSubscriber __construct called');
    }


    public static function getSubscribedEvents()
    {
        return [
            BookingPlacedEvent::class => 'onBookingPlaced',
            BookingSettledEvent::class => 'onBookingSettled',
        ];
    }

    /**
     * @param BookingPlacedEvent $event
     */
    public function onBookingPlaced(BookingPlacedEvent $event)
    {
        // TODO KEEP or NOT ?
        //
    }


    /**
     * @param BookingSettledEvent $event
     * @return Response
     */
    public function onBookingSettled(BookingSettledEvent $event)
    {
        dump('bookingSubscriber __onBookingSettled called');
        // TODO send MAIL
        return new Response(
            $event->getBookingOrder()
        );
    }
}