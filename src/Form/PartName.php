<?php

namespace App\Form;

use App\Entity\Partenaires;
use App\Entity\Reseaux;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartName extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('icone', FileType::class, [
                'label' => 'Icône (image)',
                'mapped' => true,
                'required' => false
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter le réseau'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partenaires::class,
        ]);
    }
}
