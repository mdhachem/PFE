<?php

namespace App\Form;

use App\Entity\Plan;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CreatePlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description', CKEditorType::class)
            ->add('imageFile', VichImageType::class, [
                'delete_label'          => 'Delete image ?',
                'required' => false
            ])

            ->add('category', null, [
                'placeholder' => 'select a category'
            ])
            ->add('services')
            ->add(
                'startDay',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Monday' => 0,
                        'Tuesday' => 1,
                        'Wednesday' => 2,
                        'Thursday' => 3,
                        'Friday' => 4,
                        'Saturday' => 5,
                        'Sunday' => 6,
                    ]
                ]
            )
            ->add(
                'finalDay',
                ChoiceType::class,
                [
                    'choices'  => [
                        'Monday' => 0,
                        'Tuesday' => 1,
                        'Wednesday' => 2,
                        'Thursday' => 3,
                        'Friday' => 4,
                        'Saturday' => 5,
                        'Sunday' => 6,
                    ]
                ]
            )
            ->add('startTime')
            ->add('finalTime')
            ->add('address')
            ->add('telephone')
            ->add('isBooking')
            ->add('governorate', EntityType::class, [
                'class' => 'App\Entity\Governorate',
                'placeholder' => 'Please select a governorate',
                'mapped' => false
            ]);


        $builder->get('governorate')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                //var_dump($form->getData());


                $form->getParent()->add('city', EntityType::class, [
                    'class' => 'App\Entity\City',
                    'placeholder' => 'Please select a City',
                    'choices' => $form->getData()->getCities()
                ]);
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $city = $data->getCity();

                if ($city) {
                    $form->get('governorate')->setData($city->getGovernorate());
                    //dump($city->getGovernorate()->getCities());
                    $form->add('city', EntityType::class, [
                        'class' => 'App\Entity\City',
                        'placeholder' => 'Please select a city',
                        'choices' => $city->getGovernorate()->getCities()
                    ]);
                } else {
                    //dump($city);
                    $form->add('city', EntityType::class, [
                        'class' => 'App\Entity\City',
                        'placeholder' => 'Please select a city',
                        'choices' => []
                    ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Plan::class,
        ]);
    }
}
