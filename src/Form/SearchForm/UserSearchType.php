<?php

namespace App\Form\SearchForm;

use App\Entity\SearchEntity\UserSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email'
                ]
            ])
            ->add('firstname', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'First Name'
                ]
            ])
            ->add('lastname', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Last Name'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserSearch::class,
            'method' => 'get',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
