<?php

namespace HttpKernel\EventSubscriber;

use HttpKernel\Controller\QuestionNewController;
use Fundamental\Repository\QuestionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class UserAgentSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $qc;

    public function __construct(LoggerInterface $logger, QuestionNewController $qc)
    {
        $this->logger = $logger;
        $this->qc = $qc;
    }

    public function onKernelControllerInputting(ControllerEvent $event)
    {
        //$event->setController([$this->qc, 'indexAction']);

        // Allow only for master
        if (!$event->isMasterRequest()) {
            return;
        }

        if ($this->haveQuery($event->getRequest())) {
            $event->getRequest()->attributes->set('_alpha', 'okidoki');
        }
        
        $this->logger->info('It\'s mine controller event: '.$event->getRequest()->headers->get('user-agent'));
    }

    private function haveQuery(Request $request): bool
    {
        if ($request->query->has('alpha')) {
            return true;
        }
        return true;   
    } 

    public function onKernelRequestInputting(RequestEvent $event)
    {
        //$event->getRequest()->attributes->set('_controller', 'HttpKernel\Controller\QuestionController::indexAction');

        // Add argument to every controllers
        $ua = $event->getRequest()->headers->get('user_agent');

        $event->getRequest()->attributes->set('ua', $ua);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => ['onKernelControllerInputting', 1000],
            RequestEvent::class => ['onKernelRequestInputting', 1000],
        ];
    }
}
