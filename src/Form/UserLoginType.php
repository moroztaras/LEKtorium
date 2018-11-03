<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserLoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('email',  EmailType::class, [
          'label' => 'Email'
        ]);
        $builder->add('password', PasswordType::class, [
          'label' => 'Password'
        ]);
        $builder->add('login', SubmitType::class, [
          'label' => 'Login'
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
