<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', EmailType::class, [
          'label' => 'Email',
        ]);
        $builder->add('firstName', TextType::class, [
          'label' => 'FirstName',
        ]);
        $builder->add('lastName', TextType::class, [
          'label' => 'lastName',
        ]);
        $builder->add('plainPassword', RepeatedType::class, [
          'type' => PasswordType::class,
          'invalid_message' => 'The password fields must match.',
          'required' => true,
          'first_options' => ['label' => 'Password'],
          'second_options' => ['label' => 'Repeat password'],
        ]);
        $builder->add('region', CountryType::class, [
          'label' => 'Country',
        ]);
        $builder->add('avatarFile', VichImageType::class, [
           'label' => 'Upload Avatar',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
          'data_class' => User::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
