<?php

namespace XMSecurityBundle\Security\SecurityBundle\Controller;

use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends BaseSecurityController
{
    public function loginAction(Request $request)
    {
        if ($this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('index'));
        }

        return parent::loginAction($request);
    }
}