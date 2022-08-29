<?php

namespace App\Form;

use App\Entity\Partner;
use App\Entity\Structure;
use App\Repository\PartnerRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StructureFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postalAdress', TextType::class, [
                'label' => 'Adresse postale de la structure',
            ])
            ->add('isPlanning', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('isNewsletter', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isBoissons', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isSms', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],

            ])
            ->add('isConcours', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
            ])
            // ->add('partner', EntityType::class, [
            //     'class' => Partner::class,
            //     'label' => 'Nom du partenaire',
            //      'query_builder' => function (PartnerRepository $er) {
            //          return $er->createQueryBuilder('u')
            //              ->where('u.partner LIKE :partner')
            //              ->setParameter('role', '%"ROLE_PARTENAIRE"%');
            //          },
            //     ])
      
        // ;
            // ->add('user')
            ->add('partner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}
