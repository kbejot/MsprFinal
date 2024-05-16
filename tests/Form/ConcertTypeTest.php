<?php

namespace App\Tests\Form;

use App\Form\ArtisteType;
use App\Form\ConcertType;
use App\Form\SceneType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class ConcertTypeTest extends TypeTestCase
{
    protected function getExtensions(): array
    {
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();

        $artisteType = new ArtisteType();
        $sceneType = new SceneType();

        return [
            new PreloadedExtension([$artisteType, $sceneType], []),
            new ValidatorExtension($validator),
        ];
    }

    public function testBuildForm()
    {
        $formData = [
            'artiste' => null,
            'scene' => null,
            'date' => null,
            'horaire' => null,
            'save' => 'Ajouter le concert',
        ];

        $form = $this->factory->create(ConcertType::class);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('artiste', $children);
        $this->assertArrayHasKey('scene', $children);
        $this->assertArrayHasKey('date', $children);
        $this->assertArrayHasKey('horaire', $children);
        $this->assertArrayHasKey('save', $children);

        $this->assertEquals('single_text', $form->get('date')->getConfig()->getOption('widget'));
        $this->assertEquals('yyyy-MM-dd', $form->get('date')->getConfig()->getOption('format'));
        $this->assertTrue($form->get('date')->getConfig()->getOption('html5'));

        $this->assertEquals('datetime', $form->get('horaire')->getConfig()->getOption('input'));
        $this->assertEquals('single_text', $form->get('horaire')->getConfig()->getOption('widget'));
        $this->assertFalse($form->get('horaire')->getConfig()->getOption('with_seconds'));

        $this->assertEquals('Ajouter le concert', $form->get('save')->getConfig()->getOption('label'));
    }
}

