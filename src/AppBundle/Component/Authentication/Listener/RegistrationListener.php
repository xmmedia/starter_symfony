<?php

namespace AppBundle\Component\Authentication\Listener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RegistrationListener implements EventSubscriberInterface
{
    protected $container;

    // @todo change to actual services
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationCompleted',
            FOSUserEvents::REGISTRATION_CONFIRMED => 'onRegistrationConfirmed',
        ];
    }

    /**
     * Sets the registration date on the user
     *
     * @param  FilterUserResponseEvent $event
     * @return void
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getUser();

        $user->setRegistrationDate(new \DateTime());
        $userManager->updateUser($user);

        $template = 'userRegistered';
        $to = $this->container->getParameter('admin_email');
        $mailParams = [
            'user' => $user,
        ];

        $mailManager = $this->container->get('app.mail_manager');
        $mailManager->sendEmail($template, $mailParams, $to);
    }

    public function onRegistrationConfirmed(FilterUserResponseEvent $event)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        /** @var \AppBundle\Entity\User $user */
        $user = $event->getUser();

        $user->incrementLoginCount();

        $userManager->updateUser($user);
    }
}