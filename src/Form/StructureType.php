<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

class StructureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //  ->add('roles', ChoiceType::class, [
        //         'label' => 'Type de client',
        //         'required' => true,
        //         'multiple' => false,
        //         'disabled' => true,
        //         'attr' => [
        //             'class' => 'form-select',
        //             'placeholder' => 'Type de client'
        //         ],
        //         'choices'  => [
        //                 'Structure' => 'ROLE_STRUCTURE',
        //         ],
        //     ])
            ->add('name', TextType::class, [
                'label' => 'Nom du Gérant de la structure',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom du gérant de la structure'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email du Gérant de la structure',
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
            ->add('postalAdress', TextType::class, [
                'mapped' => false,
                'label' => 'Adresse postale de la structure',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'attr' => [
                    'placeholder' => 'Saisissez l\'adresse postale de la structure'
                ]
                ])
            ->add('isActive', CheckboxType::class, [
                'label' => false,
                'label_attr' => ['class' => 'switch-custom is-active-btn'],
                'required' => false,
            ]);

            // A insérer dans le StructureType, qui est relié au StructureController
            
            $builder->add('id', EntityType::class, [
                'class' => Partner::class,
                'query_builder' => function (PartnerRepository $pr) {
                    return $pr->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'label' => 'Nom du partenaire rattaché à la structure :',
                'mapped' => false,
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
      
        ;

            // $builder->get('id')->addEventListener(
            //     FormEvents::POST_SUBMIT,
            //     function (FormEvent $event) {
           
            //         $form = $event->getForm();
            //         $form->getParent()->add('isPlanning', EntityType::class, [
            //             'class' => 'App\Entity\Partner',
            //             'placeholder' => 'Sélectionnez votre partenaire',
            //             'mapped' => false,
            //             'required' => false,
            //             'choices' => $form->getData()->getPermissions()
            //         ]);
            //     }
            // );

        // Data transformer for Roles array
        // $builder->get('roles')
        //     ->addModelTransformer(new CallbackTransformer(
        //         function ($rolesArray) {
        //              return count($rolesArray)? $rolesArray[0]: null;
        //         },
        //         function ($rolesString) {
        //              return [$rolesString];
        //         }
        // ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
