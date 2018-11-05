<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email',  EmailType::class, [
          'label' => 'Email'
        ]);
        $builder->add('firstName', TextType::class, [
          'label' => 'FirstName'
        ]);
        $builder->add('lastName', TextType::class, [
          'label' => 'lastName'
        ]);
        $builder->add('password', RepeatedType::class, array(
          'type' => PasswordType::class,
          'invalid_message' => 'The password fields must match.',
          'required' => true,
          'first_options'  => array('label' => 'Password'),
          'second_options' => array('label' => 'Repeat password'),
        ));
        $builder->add('register', SubmitType::class, [
          'label' => 'Register'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => User::class,
          'attr' => ['novalidate' => 'novalidate'],
        ));
    }
}
