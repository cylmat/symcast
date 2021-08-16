<?php

namespace UseBundle\EventSubscriber;

use KnpBundle\Event\FilterApiResponseEvent;
use KnpBundle\Event\KnpEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddMessageSubscriber implements EventSubscriberInterface
{
    public function onFilterApi(FilterApiResponseEvent $event)
    {
        $data = $event->getData();
        $data['message'] = 'Hello from Subscriber';
        $event->setData($data);
    }

    public static function getSubscribedEvents()
    {
        return [
            KnpEvents::FILTER_API => 'onFilterApi'
        ];
    }
}