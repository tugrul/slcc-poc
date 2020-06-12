<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\EventListener\AbstractSessionListener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\KernelEvents;

class SessionCacheControlSubscriber implements EventSubscriberInterface
{
    public function onKernelResponse(ResponseEvent $event)
    {

        if (!defined(AbstractSessionListener::class . '::NO_AUTO_CACHE_CONTROL_HEADER')) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->hasSession()) {
            return;
        }

        $session = $request->getSession();

        // existence of isEmpty function is not guarantee because it isn't in SessionInterface contract
        if (!($session instanceof Session) || !method_exists($session, 'isEmpty')) {

            $fields = $session->all();

            foreach ($fields as &$field) {
                if (!empty($field)) {
                    return;
                }
            }

        } elseif (!$session->isEmpty()) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->set(AbstractSessionListener::NO_AUTO_CACHE_CONTROL_HEADER, true);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => ['onKernelResponse', -999],
        ];
    }
}
