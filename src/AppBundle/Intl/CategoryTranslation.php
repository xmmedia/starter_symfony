<?php

namespace AppBundle\Intl;

use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryTranslation
{
    protected $requestStack;
    protected $em;

    protected $request;

    public function __construct(RequestStack $requestStack, ObjectManager $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;

        $this->request = $this->requestStack->getCurrentRequest();
    }

    public function findCategoryTranslation($category, $locale = null)
    {
        if (null === $locale) {
            $locale = $this->request->getLocale();
        }

        $translation = $this->em->getRepository('AppBundle:ProductCategory')
            ->findOneByLocale($category->getId(), $locale)
        ;

        return $translation;
    }

    public function findSubcategoryTranslation($subcategory, $locale = null)
    {
        if (null === $locale) {
            $locale = $this->request->getLocale();
        }

        $translation = $this->em->getRepository('AppBundle:ProductSubcategory')
            ->findOneByLocale($subcategory->getId(), $locale)
        ;

        return $translation;
    }
}