<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Photo;
use App\Entity\Section;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->image_url = $options['image_url'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Product'
            ])
            ->add('photos', FileType::class, [
                'multiple'  => true,
                'required'  => true,
                'label'     => 'advert.photo.uploader',
                'mapped'    => false,
                'attr'      => [
                    'data-default-file' => $this->image_url
                ]
            ])
            ->add('description', CKEditorType::class, [
                'required'  =>true,
                'label'     => false
            ])
            ->add('price', NumberType::class)
            ->add('section', EntityType::class, [
               'class' => Section::class,
                'choice_label' => 'label'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'label'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'            => Advert::class,
            'translation_domain'    => 'forms',
            'image_url'             =>  null
        ]);
    }
}
