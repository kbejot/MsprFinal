<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class UserTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        // create a validator and enable annotation mapping
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
            '_email' => 'test@example.com',
            '_username' => 'testuser',
            '_password' => 'password123',
        ];

        $model = new User();
        // directly submit the form data
        $form = $this->factory->create(UserType::class, $model);

        // submit the data to the form directly
        $form->submit($formData);

        // ensure the form is synchronized with the model
        $this->assertTrue($form->isSynchronized());

        // check that $model has been updated correctly
        $expected = new User();
        $expected->setEmail('test@example.com');
        $expected->setUsername('testuser');
        $expected->setPassword('password123');

        $this->assertEquals($expected, $model);

        // check the form view
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function testSubmitInvalidData()
    {
        $formData = [
            '_email' => 'invalid-email',
            '_username' => '',
            '_password' => '',
        ];

        $form = $this->factory->create(UserType::class);
        $form->submit($formData);

        $this->assertFalse($form->isValid());

        $errors = $form->get('_email')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Veuillez entrer une adresse mail valide', $errors[0]->getMessage());

        $errors = $form->get('_username')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('L\'identifiant ne doit pas être vide', $errors[0]->getMessage());

        $errors = $form->get('_password')->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le mot de passe ne doit pas être vide', $errors[0]->getMessage());
    }
}
