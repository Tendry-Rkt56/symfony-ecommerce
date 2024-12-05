<?php

namespace App\Form;

use App\Entity\Commande;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'Votre email',
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'example@gmail.com',
                ]
            ])
            ->add('password', PasswordType::class, [
                'required' => true,
                'mapped' => false,
                'label' => 'Votre mot de passe',
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Mot de passe...',
                ]
            ])
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'Nom de d\'utilisateur',
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Nom d\'utilisateur...',
                ]
            ])
            ->add('image', FileType::class, [
                'label' => 'Votre image',
                'label_attr' => [
                    'class' => 'fw-bolder',
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'btn btn-primary',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
