<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Quantity;
use App\Entity\Recipe;
use Brokoskokoli\StarRatingBundle\Form\RatingType;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')

            ->add(
                'imageFile',
                FileType::class,
                [
                    'data_class' => null
                ]
            )


            ->add('steps', CKEditorType::class)

            ->add('prepTime')
            ->add('cookTime')
            ->add('servings')
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Entrées' => 'Entrées',
                    'Plats' => 'Plats',
                    'Desserts' => 'Desserts',
                    'Sauces' => 'Sauces',
                    'Boissons' => 'Boissons'
                ]
            ])

            ->add('quantities', CollectionType::class, [
                'entry_type' => QuantityType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'by_reference' => false,
                'data' => [new Quantity()]



            ]);

        //->add('ingredients', IngredientType::class, [
        //  'data_class' => null,
        //  'translation_domain' => 'forms'

        // ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
            'translation_domain' => 'forms'
        ]);
    }
}
