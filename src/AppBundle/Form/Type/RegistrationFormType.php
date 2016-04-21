<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // we use email as the username
            ->remove('username')
            // @todo translation
            ->add('firstName', null, [
                'attr' => ['maxlength' => 255],
            ])
            ->add('lastName', null, [
                'attr' => ['maxlength' => 255],
            ])
        ;
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}