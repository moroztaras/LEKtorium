<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', TextType::class, [
          'label' => 'Title',
        ]);
        $builder->add('text', TextareaType::class, [
          'label' => 'Text',
        ]);
        $builder->add('tags', TextType::class, [
          'label' => 'Tags',
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
