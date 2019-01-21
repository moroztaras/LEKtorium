<?php

namespace App\Form\Admin;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', TextType::class, [
            'label' => 'Title',
          ])
          ->add('text', TextareaType::class, [
            'label' => 'Text',
          ])
          ->add('imageFile', VichImageType::class, [
            'label' => 'Upload Image',
          ])
          ->add('tags', CollectionType::class, [
            'entry_type' => TagType::class,
            'entry_options' => [
              'label' => false,
            ],
          ])
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
          'data_class' => Article::class,
          'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
