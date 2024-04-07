<?php

namespace App\Form;

use App\Entity\Movies;
use App\Entity\Reviews;
use App\Entity\Users;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
class ReviewFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('review_text')
        ->add('rating', IntegerType::class, [ // Sử dụng IntegerType thay cho default
            'attr' => [
                'min' => 1, // Giá trị tối thiểu là 1
                'max' => 5, // Giá trị tối đa là 5
                'class' => 'form-control' ,// Thêm class form-control
                
            ]
        ])
        ->add('movie', EntityType::class, [
            'class' => Movies::class,
            'choice_label' => 'title',
        ])
        ->add('user', EntityType::class, [
            'class' => Users::class,
            'choice_label' => 'username',
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
