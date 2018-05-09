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
     * @Route("/pattern-library-public", name="pattern_library_public")
     */
    public function patternLibraryPublicAction(Paginator $paginator)
    {
        $pagination = $paginator->paginate(
            range(1, 10), /* some random data */
            3, /* current page */
            1 /* limit per page */
        );

        return $this->render('Default/pattern_library_public.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @Route("/pattern-library-admin", name="pattern_library_admin")
     */
    public function patternLibraryAdminAction(Paginator $paginator)
    {
        $pagination = $paginator->paginate(
            range(1, 10), /* some random data */
            3, /* current page */
            1 /* limit per page */
        );

        return $this->render('Default/pattern_library_admin.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
