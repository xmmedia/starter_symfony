<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\Type\UserFormType;
use Hip\MandrillBundle\Message;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class AdminUserController extends Controller
{
    /**
     * Lists the users
     *
     * @Route("/list", name="admin_user_list")
     * @Method("GET")
     */
    public function listAction(Request $request)
    {
        $repo = $this->getDoctrine()
            ->getRepository('AppBundle:User');

        $userFilter = $this->get('app.user_filter');
        $filterForm = $userFilter->generateForm();
        $userFilter->updateSession();

        $qb = $userFilter->createQueryBuilder($repo);

        $query = $qb->getQuery();
        $pagination = $userFilter->getPagination($query);

        return $this->render('AdminUser/list.html.twig', array(
            'pagination' => $pagination,
            'user_filter_form' => $filterForm->createView(),
        ));
    }

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="admin_user_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $form = $this->createCreateForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $setPassword = $form->get('setPassword')->getData();
            if (!$setPassword) {
                $tokenGenerator = $this->get('fos_user.util.token_generator');

                // set the password to something crazy, that no one will know
                $user->setPlainPassword($tokenGenerator->generateToken());

                $user->setConfirmationToken($tokenGenerator->generateToken());
                $user->setPasswordRequestedAt(new \DateTime());
            } else {
                $password = $form->get('password')->getData();
                $user->setPlainPassword($password);
                // only enabled accounts that have a proper password
                $user->setEnabled(true);
            }

            $userManager->updateUser($user);

            // send the welcome email
            if (!$setPassword) {
                // send a link to the password reset page (from the forgot password function)
                $view = 'welcome';
                $path = $this->generateUrl('fos_user_resetting_reset', array(
                    'token' => $user->getConfirmationToken(),
                ));
            } else {
                // send an email that says they should have received the password from an admin
                $view = 'welcomeSetPassword';
                $path = $this->generateUrl('fos_user_security_login');
            }

            $template = 'AdminUser/' . $view;
            $mailParams = array(
                'user' => $user,
                'uri' => $request->getSchemeAndHttpHost() . $path,
            );

            $mailManager = $this->get('app.mail_manager');
            $mailManager->sendEmail($template, $mailParams, $user->getEmail());

            $msg_key = $setPassword ? 'created_set_password' : 'created';
            $this->addFlash('success', 'app.message.user.' . $msg_key);

            return $this->redirectToList();
        }

        $this->addFlash('warning', 'app.message.validation_errors_continue');

        return $this->render('AdminUser/create.html.twig', array(
            'entity' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $user The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createCreateForm(User $user)
    {
        $form = $this->createForm(new UserFormType(), $user, array(
            'action' => $this->generateUrl('admin_user_create'),
            'method' => 'POST',
        ));

        $form->add('button', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/new", name="admin_user_new")
     * @Method("GET")
     */
    public function newAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');

        $user = $userManager->createUser();

        $form = $this->createCreateForm($user);

        return $this->render('AdminUser/create.html.twig', array(
            'entity' => $user,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="admin_user_edit")
     * @Method("GET")
     */
    public function editAction(User $user)
    {
        $editForm = $this->createEditForm($user);

        $resetPasswordForm = $this->createResetPasswordForm($user);
        $lockUnlockForm = $this->createLockUnlockForm($user);
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('AdminUser/edit.html.twig', array(
            'entity'      => $user,
            'form'        => $editForm->createView(),
            'reset_password_form' => $resetPasswordForm->createView(),
            'lock_unlock_form'    => $lockUnlockForm->createView(),
            'delete_form'         => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a User entity.
    *
    * @param User $user The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    protected function createEditForm(User $user)
    {
        $form = $this->createForm(new UserFormType(), $user, array(
            'action' => $this->generateUrl('admin_user_update', array('id' => $user->getId())),
            'method' => 'PUT',
        ));

        $form->add('button', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Creates a form to reset the users password.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createResetPasswordForm($user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_reset_password', array('id' => $user->getId())))
            ->setMethod('POST')
            ->add('button', 'submit', array('label' => 'Reset Password'))
            ->getForm()
        ;
    }

    /**
     * Creates a form to set the user as locked or unlocked.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createLockUnlockForm($user)
    {
        if ($user->isLocked()) {
            $route = 'admin_user_unlock';
            $buttonLabel = 'Unlock Account';
        } else {
            $route = 'admin_user_lock';
            $buttonLabel = 'Lock Account';
        }

        return $this->createFormBuilder()
            ->setAction($this->generateUrl($route, array('id' => $user->getId())))
            ->setMethod('POST')
            ->add('button', 'submit', array('label' => $buttonLabel))
            ->getForm()
        ;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="admin_user_update")
     * @Method("PUT")
     */
    public function updateAction(Request $request, User $user)
    {
        $userManager = $this->get('fos_user.user_manager');

        $resetPasswordForm = $this->createResetPasswordForm($user);
        $lockUnlockForm = $this->createLockUnlockForm($user);
        $deleteForm = $this->createDeleteForm($user);

        $editForm = $this->createEditForm($user);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $setPassword = $editForm->get('setPassword')->getData();
            if ($setPassword) {
                $password = $editForm->get('password')->getData();
                $user->setPlainPassword($password);
            }

            $userManager->updateUser($user);

            $msg = $this->get('translator')->trans('app.message.entity_updated',
                array('%name%' => 'user')
            );
            $this->addFlash('success', $msg);

            return $this->redirectToList();
        }

        $this->addFlash('warning', 'app.message.validation_errors_continue');

        return $this->render('AdminUser/edit.html.twig', array(
            'entity'      => $user,
            'form'        => $editForm->createView(),
            'reset_password_form' => $resetPasswordForm->createView(),
            'lock_unlock_form'    => $lockUnlockForm->createView(),
            'delete_form'         => $deleteForm->createView(),
        ));
    }

    /**
     * Sends the user a reset password link.
     *
     * @Route("/{id}/reset-password", name="admin_user_reset_password")
     * @Method("POST")
     */
    public function resetPasswordAction(Request $request, User $user)
    {
        $form = $this->createLockUnlockForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
            $user->setPasswordRequestedAt(new \DateTime());

            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            // send a password reset email
            $path = $this->generateUrl('fos_user_resetting_reset', array(
                'token' => $user->getConfirmationToken(),
            ));
            
            $template = 'AdminUser/reset';
            $mailParams = array(
                'user' => $user,
                'uri' => $request->getSchemeAndHttpHost() . $path,
            );

            $mailManager = $this->get('app.mail_manager');
            $mailManager->sendEmail($template, $mailParams, $user->getEmail());

            // this is escaped on output in the template
            $this->addFlash('success', 'An email containing a reset password link has been sent to ' . $user->getEmail() . '.');
        }

        return $this->redirectToList();
    }

    /**
     * Enables or disables a User entity.
     *
     * @Route("/{id}/unlock", name="admin_user_unlock")
     * @Route("/{id}/lock", name="admin_user_lock")
     * @Method("POST")
     */
    public function lockUnlockAction(Request $request, User $user)
    {
        $form = $this->createLockUnlockForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($request->get('_route') == 'admin_user_unlock') {
                $user->setLocked(false);
                $user->setExpired(false);
                $user->setCredentialsExpired(false);
                $user->setCredentialsExpireAt(null);
                $msg = 'app.message.user.unlocked';
            } else {
                // only credentialsExpired actually stops the user from logging in
                // but we'll set the other ones becuase they have meaning as well
                // see: https://github.com/FriendsOfSymfony/FOSUserBundle/issues/1413#issuecomment-36443185
                $user->setLocked(true);
                $user->setExpired(true);
                $user->setCredentialsExpired(true);
                $user->setCredentialsExpireAt(new \DateTime());
                $msg = 'app.message.user.locked';
            }

            $this->get('fos_user.user_manager')
                ->updateUser($user)
            ;

            $this->addFlash('success', $msg);
        }

        return $this->redirectToList();
    }

    /**
     * "Deletes" a User entity.
     * Never actually deletes the user, but instead expires and expires the credentials.
     *
     * @Route("/{id}", name="admin_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager = $this->get('fos_user.user_manager');

            $user->setExpired(true);
            $user->setCredentialsExpired(true);
            $user->setCredentialsExpireAt(new \DateTime());
            $userManager->updateUser($user);

            $msg = $this->get('translator')->trans('app.message.entity_deleted',
                array('%name%' => 'user')
            );
            $this->addFlash('success', $msg);
        }

        return $this->redirectToList();
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    protected function createDeleteForm($user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->add('button', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * Displays the login history for a user.
     *
     * @Route("/login-history/{id}", name="admin_user_login_history")
     * @Method("GET")
     */
    public function loginHistoryAction(Request $request, User $user)
    {
        $authLogs = $user->getAuthLogs();

        $pagination = $this->get('knp_paginator')->paginate(
            $authLogs,
            $request->query->getInt('page', 1),
            50
        );

        return $this->render('AdminUser/loginHistory.html.twig', array(
            'user' => $user,
            'auth_logs' => $pagination,
        ));
    }

    /**
     * Returns the redirect response to redirect back to the user list
     * with the filter query.
     *
     * @return RedirectResponse
     */
    protected function redirectToList()
    {
        $redirectUrl = $this->generateUrl('admin_user_list');
        $redirectUrl .= '?' . $this->get('app.user_filter')->query();
        return $this->redirect($redirectUrl, 301);
    }
}