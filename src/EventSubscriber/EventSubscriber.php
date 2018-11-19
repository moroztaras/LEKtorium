<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class EventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
          KernelEvents::REQUEST => [['onRequest', 10]]
        ];
    }
    public function onRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
    }
}
