<?php

namespace App\Form\Admin;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('comment')
          ->add('approved', ChoiceType::class, [
            'choices' => [
              'Yes' => true,
              'No' => false,
            ],
            'multiple' => false, 'expanded' => true,
          ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => Comment::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
