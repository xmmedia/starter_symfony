<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints as Assert;

class UserFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'text', [
                'attr' => ['maxlength' => 254, 'autofocus' => true],
                'label' => 'Email/Username',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 3, 'max' => 254]),
                    new Assert\Email(),
                ],
            ])
            ->add('firstName', null, [
                'attr' => ['maxlength' => 255],
            ])
            ->add('lastName', null, [
                'attr' => ['maxlength' => 255],
            ])
            ->add('setPassword', 'checkbox', [
                'mapped' => false,
                'label' => 'Set Password',
                'required' => false,
            ])
            // this field is re-added below, without the constraints
            ->add('password', 'password', [
                'mapped' => false,
                'label' => 'Password',
                'required' => false,
                'attr' => ['maxlength' => 4096],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 12, 'max' => 4096]),
                ],
            ])
        ;

        // add a form event listener so the password field is not required
        // if the set password field is *not* checked
        $builder->addEventListener(
            FormEvents::SUBMIT,
            function(FormEvent $event) {
                $form = $event->getForm();
                $setPassword = $form->get('set_password')->getData();

                if (!$setPassword) {
                    $form->add('password', 'password', [
                        'mapped' => false,
                        'label' => 'Password',
                        'required' => false,
                        'attr' => ['maxlength' => 4096],
                    ]);
                }
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User'
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'app_user_edit';
    }
}
