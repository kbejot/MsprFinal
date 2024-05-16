<?php

namespace App\Tests\Form;

use App\Entity\Scene;
use App\Form\SceneType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\Validation;

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
            'number' => 10,
        ];

        $model = new Scene();
        $form = $this->factory->create(SceneType::class, $model);

        $expected = new Scene();
        $expected->setNumber(10);

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
            'number' => -10,
        ];

        $form = $this->factory->create(SceneType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());
        $errors = $form->get('number')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le nombre doit être positif', $errors[0]->getMessage());
    }
}

