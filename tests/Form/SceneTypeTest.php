<?php

namespace App\Tests\Form;

use App\Form\SceneType;
use App\Entity\Scene;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class SceneTypeTest extends TypeTestCase
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
            'number' => 2, // Valeur valide parmi les choix 1, 2, 3
        ];

        $model = new Scene();
        $form = $this->factory->create(SceneType::class, $model);

        $expected = new Scene();
        $expected->setNumber(2); // Valeur valide parmi les choix 1, 2, 3

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('number', $children);
    }

    public function testSubmitInvalidData()
    {
        $formData = [
            'number' => 10, // Valeur invalide, doit être parmi les choix 1, 2, 3
        ];

        $form = $this->factory->create(SceneType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('number')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('This value is not valid.', $errors[0]->getMessage());
    }
}
