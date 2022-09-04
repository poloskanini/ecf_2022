<?php

namespace App\Form;

use App\Entity\Permissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PermissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
            ->add('isSms', CheckboxType::class, [
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
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Permissions::class,
        ]);
    }
}
