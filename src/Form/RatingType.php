<?php

namespace App\Form;

use App\Entity\Rating;
use Brokoskokoli\StarRatingBundle\Form\RatingType as FormRatingType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', FormRatingType::class, [
                'label' => 'Rating'
            ])
            //->add('recipe')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Rating::class,
            'translation_domain' => 'forms'
        ]);
    }
}
