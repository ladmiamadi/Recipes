<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Quantity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuantityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount')
            ->add('symbol')
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'label' => 'Ingrédient',
                'choice_label' => 'name',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quantity::class,
            'translation_domain' => 'forms'
        ]);
    }
}
