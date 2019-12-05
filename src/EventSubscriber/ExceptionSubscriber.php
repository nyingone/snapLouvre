<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 10],
                ['notifyException', -10],
            ],
        ];
    }


    /**
     * @param ExceptionEvent $event
     */
    public function processException(ExceptionEvent $event)
    {
        dump($event);
    }

    /**
     * @param ExceptionEvent $event
     */
    public function notifyException(ExceptionEvent $event)
    {
        dump($event);
    }


}