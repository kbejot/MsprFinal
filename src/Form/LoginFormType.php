<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LoginFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, [
                'label' => 'Identifiant',
                'attr' => ['placeholder' => 'Identifiant']
            ])
            ->add('_password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Mot de passe']
            ]);
    }
}
