<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('messageTitle', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.contact.placeholder.messagetitle'
                ]
            ])
            ->add('messageBody', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'form.contact.placeholder.messagebody'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'form.contact.submit'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
