<?php

namespace HttpKernel\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ViewEventSubscriber implements EventSubscriberInterface
{
    public function onKernelView(ViewEvent $event)
    {
        $event->setResponse(new Response('From ViewEvent if no response defined in controller'));
    }

    public static function getSubscribedEvents()
    {
        return [
            ViewEvent::class => 'onKernelView',
        ];
    }
}
