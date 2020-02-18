<?php

namespace App\Form;

use App\Entity\Plan;
use App\Entity\Event;
use App\Entity\PlanEvent;
use App\Repository\PlanRepository;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PlanEventType extends AbstractType
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
            ->add('description', CKEditorType::class)
            ->add('imageFile', VichImageType::class, [
                'delete_label'          => 'Delete image ?',
                'required' => false,
                'download_label' => 'Download',
            ])
            ->add('price')
            ->add('dateEvent')
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
            'data_class' => Event::class,
        ]);
    }
}
