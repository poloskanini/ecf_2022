<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Partner;
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

class PartnerEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'mapped' => false,                  
                
                'label' => 'Nom de l\'établissement Partenaire',
                'required' => true,
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                'attr' => [
                    'placeholder' => 'Merci de saisir le nom du Partenaire',
                    'mapped' => false
                ]
            ])

            ->add('isPlanning', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isNewsletter', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isBoissons', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isSms', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isConcours', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
	/**
	 */
	function __construct() {
	}
}
