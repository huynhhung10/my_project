<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;


class CustomerReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'Username',
                    'style' => 'width: 100%; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'Email',
                    'style' => 'width: 100%; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'
                ]
            ])
            ->add('phonenumber', TextType::class, [
                'attr' => [
                    'class' => 'custom-input',
                    'placeholder' => 'Phone Number',
                    'style' => 'width: 100%; margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'
                ]
            ])
            ->add('reviewtext', TextareaType::class, [
                'label' => false,
                'required' => false,

                'attr' => [
                    'id' => 'content',
                    'name' => 'content',
                    'rows' => 4,
                    'style' => 'width: 100%;', 'margin-bottom: 20px;', 'padding: 10px;', 'border: 1px solid #ddd;', 'border-radius: 4px',

                ],
            ])
            ->add('img', null, [
                'label' => false,
                'required' => false,
                'data' => '/frontend/img/account/blank.jpg',
                'attr' => [
                    'style' => 'display: none;',
                ],
            ])
            ->add('rating', IntegerType::class, [
                'label' => false,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'style' => 'display: none;',
                    'class' => 'star-rating-input', // Add a class for JavaScript interaction
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save', // Label for the submit button
                'attr' => [
                    'class' => 'btn btn-primary' // Add classes for styling (assuming Bootstrap classes)
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
