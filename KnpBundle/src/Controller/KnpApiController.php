<?php

namespace KnpBundle\Controller;

use KnpBundle\Event\FilterApiResponseEvent;
use KnpBundle\Event\KnpEvents;
use KnpBundle\Service\KnpIpsum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class KnpApiController extends AbstractController
{
    private $knpIpsum;
    private $eventDispatcher;

    public function __construct(KnpIpsum $knpIpsum, ?EventDispatcherInterface $eventDispatcher = null)
    {
        $this->knpIpsum = $knpIpsum;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function index()
    {
        $data = [
            'paragraphs' => $this->knpIpsum->getParagraphs()
        ];

        $event = new FilterApiResponseEvent($data);

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch($event, KnpEvents::FILTER_API);
        }

        return $this->json($event->getData());
    }
}