<?php


namespace App\EventSubscriber;


use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestSubscriber implements EventSubscriberInterface
{
    public function onKernelController(ControllerEvent $event)
    {
        $contentType = $event->getRequest()->headers->get("Content-Type");
        if (strtolower($contentType) === strtolower("application/json")) {
            $data = json_decode($event->getRequest()->getContent(), true);
            $event->getRequest()->request->replace(is_array($data) ? $data : array());
        }

    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

}