<?php

namespace App\Form;

use App\Entity\Genres;
use App\Entity\Movies;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MoviesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'label' => 'Tên phim',
            'attr' => [
              'class' => 'form-control',
            ],
            'constraints' => [
              new NotBlank(['message' => 'Tên tài khoản không được để trống.']),
            ],
          ])
        ->add('decription', TextareaType::class, [ 
            'label' => 'Mô tả',
            'attr' => [
                'class' => 'form-control',
              ],
              'constraints' => [
                new NotBlank(['message' => 'Tên tài khoản không được để trống.']),
              ],
        ])
        ->add('img')
        ->add('genre', EntityType::class, [
            'class' => Genres::class,
            'choice_label' => 'name',
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
