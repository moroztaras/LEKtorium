<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('title', TextType::class, [
          'label' => 'title'
        ]);
        $builder->add('text', TextType::class, [
          'label' => 'text'
        ]);
        $builder->add('author', TextType::class, [
          'label' => 'author'
        ]);
        $builder->add('submit', SubmitType::class, [
          'label' => 'Add'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
          'data_class' => Article::class
        ]);
    }
}
