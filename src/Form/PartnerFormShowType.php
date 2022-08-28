<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Partner;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PartnerFormShowType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du partenaire',
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
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'label' => 'Nom de l\'utilisateur',
            //      'query_builder' => function (UserRepository $er) {
            //          return $er->createQueryBuilder('u')
            //              ->where('u.roles LIKE :role')
            //              ->setParameter('role', '%"ROLE_PARTENAIRE"%');
            //          },
            //     ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partner::class,
        ]);
    }
}
