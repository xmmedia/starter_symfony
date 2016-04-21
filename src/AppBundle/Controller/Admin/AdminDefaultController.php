<?php

namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * AdminDefault controller
 *
 * @Route("/admin")
 */
class AdminDefaultController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function indexAction()
    {
        return $this->render('Admin/index.html.twig');
    }
}