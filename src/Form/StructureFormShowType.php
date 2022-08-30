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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StructureFormShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postalAdress', TextType::class, [
                'label' => 'Adresse postale de la structure',
                'disabled' => true,
            ])
            ->add('isPlanning', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => true,
            ])
            ->add('isNewsletter', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => true,
            ])
            ->add('isBoissons', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => true,
            ])
            ->add('isSms', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => true,
            ])
            ->add('isConcours', CheckboxType::class, [
                'required' => false,
                'label' => false,
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => true,
            ])
            ->add('user')
            ->add('partner', TextType::class, [
                'disabled' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Structure::class,
        ]);
    }
}
