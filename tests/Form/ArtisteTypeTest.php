<?php

namespace App\Tests\Form;

use App\Entity\Artiste;
use App\Form\ArtisteType;
use PHPUnit\Framework\Attributes\Group;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

#[Group('form')]
class ArtisteTypeTest extends TypeTestCase
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
            'name' => 'Artiste Valide',
        ];

        $model = new Artiste();
        $form = $this->factory->create(ArtisteType::class, $model);

        $expected = new Artiste();
        $expected->setName('Artiste Valide');

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('name', $children);
    }

    public function testSubmitInvalidData()
    {
        // Test avec un nom vide
        $formData = [
            'name' => '',
        ];

        $form = $this->factory->create(ArtisteType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('name')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nom ne peut pas être vide', $errors[0]->getMessage());

        // Test avec un nom trop court
        $formData = [
            'name' => 'A',
        ];

        $form = $this->factory->create(ArtisteType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('name')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nom doit comporter au moins 2 caractères', $errors[0]->getMessage());

        // Test avec un nom trop long
        $formData = [
            'name' => str_repeat('a', 51),
        ];

        $form = $this->factory->create(ArtisteType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('name')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nom ne peut pas dépasser 50 caractères', $errors[0]->getMessage());
    }
}


