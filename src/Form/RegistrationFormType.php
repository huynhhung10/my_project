<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\EmailType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'label' => false,
                'constraints' => [
                    new NotBlank(['message' => 'không được để trống.']),
                    new Email(['message' => 'Email không hợp lệ.']),
                ],
                'attr' => ['class' => 'text email', 'autocomplete' => 'email', 'placeholder' => 'Email', 'name' => 'email']
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
            ->add('plainPassword', RepeatedType::class, [
                'label' => false,
                'mapped' => false,
                'required' => true,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'attr' => ['autocomplete' => 'new-password', 'name' => 'password', 'class' => 'text'],
                'constraints' => [

                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),

                ],
                'first_options'  => ['label' => false,  'attr' => ['autocomplete' => 'new-password', 'name' => 'password', 'class' => 'text', 'placeholder' => 'password'],],
                'second_options' => ['label' => false, 'attr' => ['autocomplete' => 'new-password', 'name' => 'password', 'class' => 'text w3lpass', 'placeholder' => 'repeat password'],],

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
