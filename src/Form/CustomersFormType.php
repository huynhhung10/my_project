<?php

namespace App\Form;

use App\Entity\Customers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Customer formm type.
 */
class CustomersFormType extends AbstractType {

  /**
   * Build form.
   */
  public function buildForm(FormBuilderInterface $builder, array $options): void {
    $builder
      ->add('username', TextType::class, [
        'label' => 'Tên tài khoản',
        'attr' => [
          'class' => 'form-control',
        ],
      ])
      ->add('password', TextType::class, [
        'label' => 'Mật khẩu',
        'attr' => [
          'class' => 'form-control',
          'id' => 'passwordField',
        ],
      ])
      ->add('img')
      ->add('email', EmailType::class, [
        'label' => 'Email',
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new NotBlank(['message' => 'Email không được để trống.']),
          new Email(['message' => 'Email không hợp lệ.']),
        ],
      ])
      ->add('phonenumber', TelType::class, [
        'label' => 'Số điện thoại',
        'attr' => [
          'class' => 'form-control',
        ],
        'constraints' => [
          new NotBlank(['message' => 'Số điện thoại không được để trống.']),
          new Regex([
            'pattern' => '/^[0-9]{10,11}$/',
            'message' => 'Số điện thoại không hợp lệ.',
          ]),
        ],
      ])
      ->add('status', CheckboxType::class, [
        'label' => 'Trạng thái hoặt động',
        'label_attr' => [
          'class' => 'form-check-label',
        ],
        'attr' => [
          'class' => 'form-check-input',
        ],
        'required' => FALSE,
        'data' => TRUE,
      ])
      ->add('save', SubmitType::class, [
        'label' => 'Lưu người dùng',
        'attr' => [
          'class' => 'btn btn-primary me-2',
        ],
      ]);
  }

  /**
   * Configure options.
   */
  public function configureOptions(OptionsResolver $resolver): void {
    $resolver->setDefaults([
      'data_class' => Customers::class,
    ]);
  }

}
