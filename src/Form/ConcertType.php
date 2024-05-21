<?php

namespace App\Form;

use App\Entity\Concert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Validator\Constraints as Assert;

class ConcertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('artiste', ArtisteType::class)
            ->add('scene', SceneType::class)
            ->add('date', ChoiceType::class, [
                'choices' => [
                    '21/05/2024' => '2024-05-21',
                    '22/05/2024' => '2024-05-22',
                    '23/05/2024' => '2024-05-23',
                ],
                'label' => 'Date du concert',
                'placeholder' => 'Sélectionnez une date',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner une date']),
                ],
            ])
            ->add('horaire', ChoiceType::class, [
                'choices' => [
                    '15:00' => '15:00:00',
                    '16:00' => '16:00:00',
                    '17:00' => '17:00:00',
                ],
                'label' => 'Heure du concert',
                'placeholder' => 'Sélectionnez une heure',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner une heure']),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter le concert', 'attr' => ['class' => 'btn btn-primary']]);

        // Transform string dates to DateTime objects
        $builder->get('date')
            ->addModelTransformer(new CallbackTransformer(
                function ($dateAsString) {
                    return $dateAsString;
                },
                function ($dateAsString) {
                    return new \DateTime($dateAsString);
                }
            ));

        // Transform string times to DateTime objects
        $builder->get('horaire')
            ->addModelTransformer(new CallbackTransformer(
                function ($timeAsString) {
                    return $timeAsString;
                },
                function ($timeAsString) {
                    return \DateTime::createFromFormat('H:i:s', $timeAsString);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concert::class,
        ]);
    }
}
