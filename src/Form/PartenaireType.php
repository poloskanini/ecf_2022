<?php

namespace App\Form;

use App\Entity\Partenaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PartenaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-4',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Merci de saisir votre adresse email',
                    'class' => 'form-control mb-4'
                ],
                'label' => 'Votre email',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => false,
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'required' => true,
                'first_options' => [
                    'label' => 'Entrez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de saisir votre mot de passe',
                        'class' => 'form-control mb-4',
                        'autocomplete' => true
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Merci de confirmer votre mot de passe',
                        'class' => 'form-control mb-4',
                    ],
                ]
            ])
            ->add('Planning', CheckboxType::class, [
                'attr' => ['class' => 'form-widget form-switch form-check form-check-input mb-4'],
                'required' => false
            ])
            ->add('Newsletter', CheckboxType::class, [
                'attr' => ['class' => 'form-widget form-switch form-check form-check-input mb-4'],
                'required' => false
            ])
            ->add('Boissons', CheckboxType::class, [
                'attr' => ['class' => 'form-widget form-switch form-check form-check-input mb-4'],
                'required' => false
            ])
            ->add('SMS', CheckboxType::class, [
                'attr' => ['class' => 'form-widget form-switch form-check form-check-input mb-4'],
                'required' => false,
                'label' => 'SMS'
            ])
            ->add('Promotions', CheckboxType::class, [
                'attr' => ['class' => 'form-widget form-switch form-check form-check-input mb-4'],
                'required' => false
            ])
            ->add('Concours', CheckboxType::class, [
                'attr' => ['class' => 'form-widget form-switch form-check form-check-input mb-4'],
                'required' => false
            ])
            ->add('Submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mb-4'],
                    'label' => 'Ajouter un partenaire',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partenaire::class,
        ]);
    }
}
