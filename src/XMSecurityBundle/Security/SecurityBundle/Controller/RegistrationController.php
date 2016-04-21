<?php

namespace XMSecurityBundle\Security\SecurityBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseRegistrationController;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends BaseRegistrationController
{
    public function registerAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('index'));
        }

        return parent::registerAction($request);
    }
}