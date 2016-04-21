<?php

namespace AppBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\RequestStack;

use Gedmo\IpTraceable\IpTraceableListener;

/**
 * IpTraceSubscriber
 */
class IpTraceSubscriber implements EventSubscriberInterface
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var IpTraceableListener
     */
    private $ipTraceableListener;

    public function __construct(IpTraceableListener $ipTraceableListener, RequestStack $requestStack = null)
    {
        $this->ipTraceableListener = $ipTraceableListener;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * Set the username from the security context by listening on core.request
     *
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (null === $this->request) {
            return;
        }

        // If you use a cache like Varnish, you may want to set a proxy to Request::getClientIp() method
        // $this->request->setTrustedProxies(array('127.0.0.1'));

        // $ip = $_SERVER['REMOTE_ADDR'];
        $ip = $this->request->getClientIp();

        if (null !== $ip) {
            $this->ipTraceableListener->setIpValue($ip);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => 'onKernelRequest',
        );
    }
}