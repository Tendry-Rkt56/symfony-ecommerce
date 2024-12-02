<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom de l\'article',
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'fw-bolder',
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Prix de l\'article',
                    'required' => true
                ],
                'label_attr' => [
                    'class' => 'fw-bolder',
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Séléctionner une catégorie',
                'label' => 'Categorie associée',
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
                'attr' => [
                    'class' => 'form-select'
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false, 
                'label' => 'Image associée',
                'mapped' => false,
                'label_attr' => [
                    'class' => 'fw-bolder'
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add("save", SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
