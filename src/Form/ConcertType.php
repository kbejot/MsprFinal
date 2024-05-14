<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artiste', ArtisteType::class)
            ->add('scene', SceneType::class)
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'html5' => true,
            ])
            ->add('horaire', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'single_text',
                'with_seconds' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter le concert']);
    }
}
