<?php

namespace XMSecurityBundle\Security\SecurityBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class XMSecurityBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
