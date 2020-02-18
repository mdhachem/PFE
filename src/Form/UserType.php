<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('imageFile', VichImageType::class, [
                'delete_label'          => 'Delete image ?',
                'required' => false
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('address')
            ->add('telephone')
            ->add('role', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'Customer' => 0,
                    'Partner' => 1,
                    'Admin' => 2
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
