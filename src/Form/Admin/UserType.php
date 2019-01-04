<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('firstName', TextType::class, [
            'label' => 'FirstName',
          ])
          ->add('lastName', TextType::class, [
            'label' => 'LastName',
          ])
          ->add('tempRoles', ChoiceType::class,
                [
                  'label' => 'Roles',
                  'choices' => [
                    'role_reader' => 'ROLE_READER',
                    'role_blogger' => 'ROLE_BLOGGER',
                  ],
                  'attr' => [
                    'class' => 'inline-radio',
                  ],
                  'multiple' => false,
                  'expanded' => true,
                ]
              )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => User::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
