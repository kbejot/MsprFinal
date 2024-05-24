<?php

namespace App\Tests\Form;

use App\Form\LoginFormType;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class LoginFormTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
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
            '_username' => 'validusername',
            '_password' => 'validpassword123',
        ];

        $form = $this->factory->create(LoginFormType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitInvalidData()
    {
        $formData = [
            '_username' => '',
            '_password' => '',
        ];

        $form = $this->factory->create(LoginFormType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

        $errors = $form->get('_username')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('L\'identifiant ne doit pas être vide', $errors[0]->getMessage());

        $errors = $form->get('_password')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le mot de passe ne doit pas être vide', $errors[0]->getMessage());
    }
}
