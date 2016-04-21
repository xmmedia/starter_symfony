<?php

namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use AppBundle\Entity\User;

class DoctrineExtensionListener implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // @todo not sure if this will break at some
        // can't use security.authorization_checker service because it breaks
        // the profiler because it's not being a firewall
        $token = $this->container->get('security.token_storage')->getToken();
        if (null !== $token) {
            if ($token->getUser() instanceof User) {
                $loggable = $this->container->get('gedmo.listener.loggable');
                $loggable->setUsername($token->getUser()->getUsername());
            }
        }
    }
}