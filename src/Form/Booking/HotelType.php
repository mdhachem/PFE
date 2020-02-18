<?php

namespace App\Form\Booking;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class HotelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('finalDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('guests', IntegerType::class, [
                'data' => 1
            ])
            ->add('RoomType', ChoiceType::class, [
                'placeholder' => 'Room Type',
                'choices' => [
                    'Single Room' => 'Single Room',
                    'Double Room' => 'Double Room',
                    'Suite Room' => 'Double Room'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
