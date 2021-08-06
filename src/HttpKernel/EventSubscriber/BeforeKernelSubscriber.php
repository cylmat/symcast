<?php

namespace HttpKernel\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class BeforeKernelSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(RequestEvent $event)
    {
        //$event->setResponse(new Response('Called from BeforeKernelSubscriber!'));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvent::class => 'onKernelRequest',
        ];
    }
}
