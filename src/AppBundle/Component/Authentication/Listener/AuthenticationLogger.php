<?php

namespace AppBundle\Component\Authentication\Listener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpFoundation\RequestStack;
use FOS\UserBundle\Doctrine\UserManager;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use AppBundle\Entity\AuthLog;

/**
 * Idea from: http://stackoverflow.com/questions/11180351/symfony2-after-successful-login-event-perform-set-of-actions
 */
class AuthenticationLogger
{
   protected $tokenStorage;
   protected $requestStack;
   protected $userManager;
   protected $em;

   public function __construct(TokenStorage $tokenStorage, RequestStack $requestStack, UserManager $userManager, ObjectManager $em)
   {
      $this->tokenStorage = $tokenStorage;
      $this->requestStack = $requestStack;
      $this->userManager = $userManager;
      $this->em = $em;
   }

   public function success(InteractiveLoginEvent $event)
   {
      $user = $this->tokenStorage->getToken()->getUser();
      $request = $this->requestStack->getCurrentRequest();

      $user->incrementLoginCount();

      $this->userManager->updateUser($user);

      $authLog = $this->createAuthLog();
      $authLog->setSuccess(true);
      $authLog->setUser($user);
      $authLog->setUsername($user->getUsername());

      $this->em->persist($authLog);
      $this->em->flush();
   }

   public function failure(AuthenticationFailureEvent $event)
   {
      $request = $this->requestStack->getCurrentRequest();
      $exceptionMsg = $event->getAuthenticationException()->getPrevious()->getMessage();

      $authLog = $this->createAuthLog();
      $authLog->setSuccess(false);
      $authLog->setUsername($request->request->get('_username'));
      $authLog->setMessage($exceptionMsg);

      $this->em->persist($authLog);
      $this->em->flush();
   }

   protected function createAuthLog()
   {
      $request = $this->requestStack->getCurrentRequest();

      $authLog = new AuthLog();
      $authLog->setUserAgent($request->headers->get('User-Agent'));
      $authLog->setIpAddress($request->getClientIp());

      return $authLog;
   }
}