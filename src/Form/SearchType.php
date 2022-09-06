<?php 

namespace App\Form;

use App\Entity\User;
use App\Classe\Search;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
   $builder
    ->add('string', TextType::class, [
      'label' => false,
      'required' => false,
      'attr' => [
        'placeholder' => 'Rechercher...'
      ]
      ])
    // ->add('users', EntityType::class, [
    //   'class' => User::class,
    //   'required' => false,
    //   'label' => false,
    //   'multiple' => true,
    //   'expanded' => true
    // ])
    ->add('submit', SubmitType::class, [
      'label' => 'Filtrer',
      'attr' => [
        'class' => 'btn-block btn-sm btn-secondary'
      ]
    ]
    )
    
    ;
  }

  public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

  public function getBlockPrefix()
  {
    return '';
  }
}