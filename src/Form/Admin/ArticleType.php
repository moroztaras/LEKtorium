<?php

namespace App\Form\Admin;

use App\Entity\Article;
use App\Form\Admin\Model\ArticleModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
        $builder->add('image', FileType::class, [
          'label' => 'Upload Image'
        ]);
        $builder->add('tagsInput', TextType::class, [
          'label' => 'Tags',
        ]);
    }

//    public function configureOptions(OptionsResolver $resolver)
//    {
//        $resolver->setDefaults([
//          'data_class' => Article::class,
//          'attr' => ['novalidate' => 'novalidate'],
//        ]);
//    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('entity_manager');
        $resolver->setDefaults([
          'data_class' => ArticleModel::class,
          'entity_manager' => null,
        ]);
    }
}
