<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditionUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.edit.placeholder.firstname'
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.edit.placeholder.lastname'
                ]
            ])
            ->add('phone', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.edit.placeholder.phone'
                ]
            ])
            ->add('avatar', FileType::class, [
                'required' => false,
                'label' => 'form.edit.avatar',
                'data_class' => null,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "form.edit"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms',
        ]);
    }
}