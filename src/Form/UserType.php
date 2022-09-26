<?php

namespace App\Form;

use Assert\Regex;
use App\Entity\User;
use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // dd($options['csrf_token_manager']);

        $user = $options['data'] ?? null;
        $isEdit = $user && $user->getId();

        $isEdit = $options['isEdit'];

        $builder
            ->add('roles', ChoiceType::class, [
                'label' => 'Type d\'utilisateur',
                'required' => true,
                'disabled' => true,
                'multiple' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
                'choices'  => [
                        'Partenaire' => 'ROLE_PARTENAIRE',
                        'Structure' => 'ROLE_STRUCTURE'
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'utilisateur',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email du partenaire',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir une adresse email'
                ]
            ])
            ->add('isActive', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom is-active-btn'],
                'required' => false,
            ]);
        

            // Affichage conditionnel du Password.
            // On vérifie si on se trouve dans le cas ou l’option isEdit est à true. Si ce n’est pas le cas, alors j’affiche l’édition du mot de passe sinon je l’ignore.
            if (!$isEdit) {
                $builder->add('password', RepeatedType::class, [
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
                ]);
            }
            ;

        // Data transformer for Roles array
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                     return count($rolesArray)? $rolesArray[0]: null;
                },
                function ($rolesString) {
                     return [$rolesString];
                }
        ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'isEdit' => false,
        ]);

        $resolver->setAllowedTypes('isEdit', 'bool');
    }
}
