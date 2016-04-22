<?php

namespace AppBundle\Component\Filter;

use AppBundle\Component\FilterComponent;

class UserFilter extends FilterComponent
{
    protected $sessionKey = 'user_list';

    public function filterDefaults()
    {
        return [
            'text' => null,
            'hide_locked' => true,
            'user_type' => 'all',
        ];
    }

    public function generateForm()
    {
        $formBuilder = $this->createFormBuilder();

        $formBuilder->add('text', null, array(
            'label' => 'Search',
            'attr' => array('maxlength' => 150),
        ))
            ->add('hide_locked', 'checkbox', array(
                'label' => 'Hide Locked Users',
            ))
            ->add('user_type', 'choice', array(
                'choices' => array(
                    'all' => 'All Users',
                    'non_admin' => 'Exclude Administrators',
                    'admin_only' => 'Only Administrators',
                ),
                'label' => 'User Type',
            ))
        ;

        $this->form = $formBuilder->getForm();
        $this->form->handleRequest($this->request);

        return $this->form;
    }

    /**
     * Creates the query builder for the system list.
     *
     * @param  SystemRepository $repo The system repo
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder($repo)
    {
        $qb = $repo->createQueryBuilder('u')
            ->orderBy('u.email', 'ASC')
        ;

        $filters = $this->getFormData();

        if ($filters['hide_locked']) {
            $qb->andWhere('u.locked = false');
        }
        if (!empty($filters['text'])) {
            $qb->andWhere($qb->expr()->orX(
                $qb->expr()->like('u.email', ':text'),
                $qb->expr()->like('u.name', ':text')
            ))
                ->setParameter('text', '%' . $filters['text'] . '%')
            ;
        }
        if ($filters['user_type'] != 'all') {
            if ($filters['user_type'] == 'admin_only') {
                $role = 'ROLE_SUPER_ADMIN';
            } else {
                $role = 'ROLE_USER';
            }
            $qb->andWhere('u.roles LIKE :role')
                // parameter includes double quotes so we don't accidentally get a different role
                // such as ADMIN retrieving ROLE_ADMIN and ROLE_SUPER_ADMIN
                ->setParameter('role', '%"' . $role . '"%')
            ;
        }

        return $qb;
    }
}