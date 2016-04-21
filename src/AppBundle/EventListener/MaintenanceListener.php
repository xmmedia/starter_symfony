<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * From: http://www.wenigersh.com/blog/post/maintenance-mode-for-symfony-2-applications
 */
class MaintenanceListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $inMaintenanceUntil = $this->container->hasParameter('in_maintenance_until') ? $this->container->getParameter('in_maintenance_until') : false;
        $maintenance = $this->container->hasParameter('maintenance') ? $this->container->getParameter('maintenance') : false;

        if ($maintenance) {
            $engine = $this->container->get('templating');
            $content = $engine->render('::maintenance.html.twig', array(
                'in_maintenance_until' => $inMaintenanceUntil,
            ));
            $event->setResponse(new Response($content, 503));
            $event->stopPropagation();
        }
    }
}