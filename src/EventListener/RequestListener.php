<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    public function OnKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
    }
}
