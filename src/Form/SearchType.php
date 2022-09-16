<?php 

namespace App\Form;

use App\Entity\User;
use App\Classe\Search;
use App\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class SearchType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
   $builder
    ->add('string', TextType::class, [
      'label' => false,
      'required' => false,
      'attr' => [
        'placeholder' => 'Rechercher...',
      ],
    ]);

    $builder
    ->add('active', CheckboxType::class, [
      'label' => 'Actif',
      'required' => false
    ])
    ->add('inactive', CheckboxType::class, [
      'label' => 'Inactif',
      'required' => false
    ])
    ->add('submit', SubmitType::class, [
      'label' => 'OK',
      'attr' => [
        'class' => 'btn-secondary',
        'style' => '--bs-btn-padding-y: .10rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;'
      ],
    ])
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

  public function getBlockPrefix()
  {
    return '';
  }
}