<?php

namespace App\Form\SearchForm;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Entity\SearchEntity\HomeSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HomeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plan', TextType::class, [
                'attr' => [
                    'placeholder' => 'What are you looking for...'
                ]
            ])
            ->add('place', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Where'
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'placeholder' => 'All Categories',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HomeSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
