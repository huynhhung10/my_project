<?php

namespace App\Form;

use App\Entity\Genres;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenresFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Tên thể loại',
            'attr' => [
              'class' => 'form-control',
            ],
          ])
            ->add('save', SubmitType::class, [
                'label' => 'Lưu thể loại',
                'attr' => [
                  'class' => 'btn btn-primary me-2',
                ],
              ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Genres::class,
        ]);
    }
}
