<?php

namespace App\Form;

use App\Entity\Genres;
use App\Entity\Movies;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoviesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title')
        ->add('genre', EntityType::class, [
            'class' => Genres::class,
            'choice_label' => 'name', // Assuming 'name' is the property of Genres entity you want to display
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Lưu phim',
            'attr' => [
              'class' => 'btn btn-primary me-2',
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movies::class,
        ]);
    }
}
