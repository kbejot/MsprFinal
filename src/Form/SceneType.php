<?php

namespace App\Form;

use App\Entity\Scene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SceneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', ChoiceType::class, [
                'label' => false,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La valeur ne doit pas être vide']),
                    new Assert\Positive(['message' => 'Le nombre doit être positif']),
                    new Assert\LessThan([
                        'value' => 4,
                        'message' => 'Le nombre doit être 1, 2 ou 3',
                    ]),
                ],
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Scene::class,
        ]);
    }
}
