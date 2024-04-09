<?php

namespace App\Form;

use App\Entity\Movies;
use App\Entity\Reviews;
use App\Entity\Customers;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    
        ->add('movie', EntityType::class, [
            'class' => Movies::class,
            'choice_label' => 'title',
           
        ])
        ->add('customer', EntityType::class, [
            'class' => Customers::class,
            'choice_label' => 'username',
        ])
        ->add('reviewtext', TextType::class, [
            'attr' => [
              'class' => 'form-control',
            ],
          ])
        ->add('rating', IntegerType::class, [ // Sử dụng IntegerType thay cho default
            'attr' => [
                'min' => 1, // Giá trị tối thiểu là 1
                'max' => 5, // Giá trị tối đa là 5
                'class' => 'form-control' ,// Thêm class form-control
                
            ]
           
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Lưu bình luận',
            'attr' => [
              'class' => 'btn btn-primary me-2',
            ],
        ]);
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Reviews::class,
       
    ]);
}
}
