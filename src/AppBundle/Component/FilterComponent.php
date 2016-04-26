<?php

namespace AppBundle\Component;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FilterComponent
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var Session
     */
    protected $session;

    /**
     * The form object after it's been built.
     *
     * @var Form
     */
    protected $form;

    /**
     * Where the key for the session storage.
     *
     * @var string
     */
    protected $sessionKey;

    /**
     * The record count per page.
     *
     * @var integer
     */
    protected $pageLimit = 20;

    /**
     * @param RequestStack       $requestStack The request stack
     * @param ContainerInterface $container    The container interface
     */
    public function __construct(RequestStack $requestStack, ContainerInterface $container)
    {
        $this->container = $container;
        $this->request = $requestStack->getCurrentRequest();
        $this->session = $this->container->get('session');
    }

    /**
     * Returns the defaults for the session.
     *
     * @return array
     */
    public function sessionDefaults()
    {
        return [
            'filters' => [],
            'page' => null,
            'ids' => [],
        ];
    }

    /**
     * Returns the default filters.
     *
     * @return array
     */
    public function filterDefaults()
    {
        return [];
    }

    /**
     * Sets the key in the session.
     * The filters are merged with the defaults as well as the session as a whole.
     */
    public function updateSession()
    {
        $existingSession = $this->getSession();
        if ($this->form) {
            $filters = $this->form->getData() + $this->filterDefaults();
        } else {
            $filters = $this->filterDefaults();
        }

        $currentValues = [
            'filters' => $filters,
            'page' => $this->pageFromQuery(),
        ];

        $newSession = array_merge($this->sessionDefaults(), $existingSession, $currentValues);

        $this->session->set($this->sessionKey, $newSession);
    }

    /**
     * Merges the passed array with the session data and updates the session.
     *
     * @param  array  $newValues The values in the session to replace.
     */
    public function mergeSession(array $newValues)
    {
        $existing = $this->getSession();

        $new = array_merge($existing, $newValues);

        $this->session->set($this->sessionKey, $new);
    }

    /**
     * Gets all or a single key from the session.
     *
     * @param  string $key The session key to retrieve or null for the entire session.
     *
     * @return mixed
     */
    public function getSession($key = null)
    {
        $sessionData = (array) $this->session->get($this->sessionKey) + $this->sessionDefaults();

        if (null === $key) {
            return $sessionData;
        } else if (array_key_exists($key, $sessionData)) {
            return $sessionData[$key];
        } else {
            return;
        }
    }

    /**
     * Returns the current page from the query.
     * If the page is not set, it will return page 1.
     *
     * @return int
     */
    public function pageFromQuery()
    {
        $page = $this->request->query->getInt('page');

        return ($page > 0) ? $page : 1;
    }

    /**
     * Returns the query string based on the filters and page in the session.
     *
     * @return string
     */
    public function query()
    {
        $filters = $this->getSession('filters');
        $page = $this->getSession('page');

        $queryData = [
            'form' => $filters,
            'page' => $page,
        ];

        return http_build_query($queryData);
    }

    /**
     * Creates the filter form builder.
     *
     * @return Form
     */
    public function createFormBuilder()
    {
        $formFactory = $this->container->get('form.factory');
        $defaults = $this->filterDefaults();

        $formBuilder = $formFactory->createBuilder('form', $defaults, [
            'method' => 'GET',
            'csrf_protection' => false,
            'attr' => ['novalidate' => 'novalidate'],
        ]);

        return $formBuilder;
    }

    /**
     * Creates the filter form.
     *
     * @return Form
     */
    public function generateForm()
    {
        $formBuilder = $this->createFormBuilder();

        $this->form = $formBuilder->getForm();
        $this->form->handleRequest($this->request);

        return $this->form;
    }

    /**
     * Stores the full list of record IDs in the session.
     *
     * @param  QueryBuilder $idOnlyQb The query builder
     *
     * @return void
     */
    public function storeResult($idOnlyQb)
    {
        $result = $idOnlyQb->getQuery()->getArrayResult();

        $resultIds = array_column($result, 'id');

        $this->mergeSession(['ids' => $resultIds]);
    }

    /**
     * Returns the configured KNP Paginator instance.
     *
     * @param  Query $query The query
     *
     * @return SlidingPagination
     */
    public function getPagination($query)
    {

        $paginator = $this->container->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query,
            $this->pageFromQuery(),
            $this->getPageLimit()
        );

        return $pagination;
    }

    /**
     * Finds the previous and next IDs in the record list.
     * Returns an array with the first key as the previous
     * and second key as the next system ID.
     * Either key can be NULL if there is no previous or next.
     *
     * @param  int $currentRecordId The current record ID
     *
     * @return array
     */
    public function prevNext($currentRecordId)
    {
        $recordIds = (array) $this->getSession('ids');
        $prevRecordId = $nextRecordId = null;

        $currentKey = array_search($currentRecordId, $recordIds);
        if (false !== $currentKey) {
            if ($currentKey > 0) {
                $prevRecordId = $recordIds[$currentKey - 1];
            }
            if ($currentKey < count($recordIds) - 1) {
                $nextRecordId = $recordIds[$currentKey + 1];
            }
        }

        return [$prevRecordId, $nextRecordId];
    }

    /**
     * Returns the data from the form.
     *
     * @return array
     */
    public function getFormData()
    {
        return $this->form->getData();
    }

    /**
     * Returns the page limit count.
     *
     * @return int
     */
    public function getPageLimit()
    {
        return $this->pageLimit;
    }
}