<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('Default/index.html.twig');
    }

    /**
     * @Route("/pattern-library", name="pattern_library")
     */
    public function patternLibraryAction()
    {
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            range(1, 10), /* some random data */
            1, /* current page */
            1 /* limit per page */
        );

        return $this->render('Default/pattern_library.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}