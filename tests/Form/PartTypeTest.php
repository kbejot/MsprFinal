<?php

namespace App\Tests\Form;

use App\Entity\Partenaires;
use App\Form\PartType;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

#[Group('form')]
class PartTypeTest extends TypeTestCase
{
    protected function getExtensions()
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'nom' => 'Nom Valide',
        ];

        $model = new Partenaires();
        $form = $this->factory->create(PartType::class, $model);

        $expected = new Partenaires();
        $expected->setNom('Nom Valide');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('nom', $children);
        $this->assertArrayHasKey('save', $children);
    }

    public function testSubmitInvalidData()
    {
        // Test avec un nom vide
        $formData = [
            'nom' => '',
        ];

        $form = $this->factory->create(PartType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('nom')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nom ne peut pas être vide', $errors[0]->getMessage());

        // Test avec un nom trop court
        $formData = [
            'nom' => 'A',
        ];

        $form = $this->factory->create(PartType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('nom')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nom doit comporter au moins 2 caractères', $errors[0]->getMessage());

        // Test avec un nom trop long
        $formData = [
            'nom' => str_repeat('a', 51),
        ];

        $form = $this->factory->create(PartType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('nom')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nom ne peut pas dépasser 50 caractères', $errors[0]->getMessage());
    }
}
