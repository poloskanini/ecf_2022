<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Votre nom',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre email',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir une adresse email'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => false,
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir un mot de passe'
                    ]
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Type de client',
                'required' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-select',
                    'placeholder' => 'Type de client'
                ],
                'choices'  => [
                        'Partenaire' => 'ROLE_PARTNER',
                        'Structure' => 'ROLE_STRUCTURE',
                ],
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom is-active-btn'],
                'required' => false,
            ])
            ->add('isPlanning', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('isNewsletter', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('isBoissons', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('isSMS', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
            ->add('isConcours', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'required' => false,
            ])
        ;

        // Data transformer for Roles array
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     // transform the array to a string
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     // transform the string back to an array
                     return [$rolesString];
                }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
