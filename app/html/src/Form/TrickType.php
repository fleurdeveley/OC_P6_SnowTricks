<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom de la figure'
            ])

            ->add('content', TextareaType::class, [
                'label' => 'Description'
            ])

            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'placeholder' => '--Choisir une catégorie--',
                'class' => Category::class,
                'choice_label' => function(Category $category){
                    return ucfirst($category->getName());
                }
            ])

            ->add('picture', UrlType::class, [
                'label' => 'Image de la figure',
                'attr' => ['placeholder' => 'Tapez une URL d\'image'],            
            ])

            ->add('video', UrlType::class, [
                'label' => 'Vidéo de la figure',
                'attr' => ['placeholder' => 'Tapez une URL de vidéo'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
