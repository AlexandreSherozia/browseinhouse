<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $user = $event->getData();
                $form = $event->getForm();
                if (!$user || $user->getId() === null) {
                    $form->add('pseudo', TextType::class, [
                        'required' => true,
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'form.register.placeholder.pseudo'
                        ]
                    ])
                        ->add('email', EmailType::class, [
                            'required' => true,
                            'label' => false,
                            'attr' => [
                                'placeholder' => 'form.register.placeholder.email'
                            ]
                        ])
                        ->add('password', PasswordType::class, [
                            'required' => true,
                            'label' => false,
                            'attr' => [
                                'placeholder' => 'form.register.placeholder.password'
                            ]
                        ])
                        ->add('submit', SubmitType::class, [
                            'label' => "form.register.submit"
                        ]);
                }

                else {
                    $form->add('firstname', TextType::class, [
                        'required' => false,
                        'label' => false,
                        'attr' => [
                            'placeholder' => 'form.register.placeholder.firstname'
                        ]
                    ])
                        ->add('lastname', TextType::class, [
                            'required' => false,
                            'label' => false,
                            'attr' => [
                                'placeholder' => 'form.register.placeholder.lastname'
                            ]
                        ])
                        ->add('phone', TextType::class, [
                            'required' => false,
                            'label' => false,
                            'attr' => [
                                'placeholder' => 'form.register.placeholder.phone'
                            ]
                        ])
                        ->add('submit', SubmitType::class, [
                            'label' => "form.register.edit"
                        ]);
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => User::class,
           'translation_domain' => 'forms'
        ]);
    }
}