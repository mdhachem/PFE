<?php

namespace App\Form;

use App\Entity\Plan;
use App\Entity\User;
use App\Entity\Produit;
use App\Repository\PlanRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Product;

class ProductType extends AbstractType
{

    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('imageFile', VichImageType::class)
            ->add('price')
            ->add('plan', EntityType::class, [
                'class' => Plan::class,
                'query_builder' => function (PlanRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.user = :user')
                        ->setParameter('user', $this->user);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
