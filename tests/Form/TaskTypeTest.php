<?php

namespace App\Tests\Form;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\Form\Test\TypeTestCase;

class TaskTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $title = 'Test title';
        $content = 'Test Content';

        $formData = [
            'title' => $title,
            'content' => $content
        ];

        // Manually populated object
        $object = new Task();
        $object->setTitle($title);
        $object->setContent($content);

        // Object that should be the same than the manually populated object
        $objectToCompare = new Task();

        // Form creation that we link to our objectToCompare
        $form = $this->factory->create(TaskType::class, $objectToCompare);
        $form->submit($formData);

        // Verification that form was correctly sent (data transformers didn't failed)
        static::assertTrue($form->isSynchronized());

        // Check that $objectToCompare was modified as expected when the form was submitted
        // static::assertEquals($object, $objectToCompare);
        static::assertEquals($object->getTitle(), $objectToCompare->getTitle());
        static::assertEquals($object->getContent(), $objectToCompare->getContent());

        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }

    }
}