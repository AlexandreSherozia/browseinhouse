<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('section', ChoiceType::class, [
                'choices'       =>  [
                    'Select a section' => false,
                    'Sell'      =>  'Sell',
                    'Buy'       =>  'Buy',
                    'Rent'      =>  'Rent',
                    'Services'  =>  'Service'
                ]
            ])
            ->add('label',ChoiceType::class, [
                'choices' => [
                    'Select category'   => false,
                    'Real Estate'       => 'Real Estate',
                    'Vehicle'           => 'Vehicle',
                    'Mobiles'           => 'Mobiles',
                    'Fournitures'       => 'Fournitures',
                    'Fashion'           => 'Fashion'
                ],
                'label' => 'Categories'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
