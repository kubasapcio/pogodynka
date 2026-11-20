<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function Sodium\add;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', null, [
                'attr' => [
                    'placeholder' => 'Enter city name',
                ],
            ])
            ->add('country', ChoiceType::class, [
                'choices' => [
                    'Poland' => 'PL',
                    'United States' => 'US',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'United Kingdom' => 'GB',
                ],
                'placeholder' => 'Select a country',
            ])
            ->add('latitude', NumberType::class, [
                'html5' => true,
                'attr' => [
                    'placeholder' => 'Enter latitude',
                ],
            ])
            ->add('longitude',NumberType::class, [
                'html5' => true,
                'attr' => [
            'placeholder' => 'Enter latitude',
                ],
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
