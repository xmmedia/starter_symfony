<?php

namespace AppBundle\Controller;

use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
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
    public function patternLibraryAction(Paginator $paginator)
    {
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
