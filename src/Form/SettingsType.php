<?php

namespace App\Form;

use App\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFileLogo', VichImageType::class, [
                'delete_label'          => 'Delete image ?',
                'required' => false,
                'download_label' => 'Download',
            ])
            ->add('address')
            ->add('phone')
            ->add('email')
            ->add('facebook')
            ->add('twitter')
            ->add('google')
            ->add('pinterest')
            ->add('instagram')
            ->add('update', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class,
        ]);
    }
}
