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

class UserShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['data'] ?? null;
        $isEdit = $user && $user->getId();

        $isEdit = $options['isEdit'];
        
        $builder
            ->add('name', TextType::class, [
                'disabled' => true,
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
                'disabled' => true,
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
            ->add('roles', ChoiceType::class, [
                'disabled' => true,
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
                'disabled' => true,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom is-active-btn'],
                'required' => false,
            ]);

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
            'isEdit' => false
        ]);

        $resolver->setAllowedTypes('isEdit', 'bool');

    }
}
