<?php

namespace App\Form;

use App\Entity\Plan;
use App\Entity\Photo;
use App\Repository\PlanRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PhotoType extends AbstractType
{

    private $user;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->user = $tokenStorage->getToken()->getUser();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('imageFile', VichImageType::class)
            ->add('plan', EntityType::class, [
                'class' => Plan::class,
                'query_builder' => function (PlanRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.user = :user')
                        ->setParameter('user', $this->user);
                },
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }
}
