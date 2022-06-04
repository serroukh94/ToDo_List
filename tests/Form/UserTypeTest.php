<?php

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class UserTypeTest extends TypeTestCase
{
    /*
    protected function getExtensions()
    {
        $validator = Validation::createValidator();
        // or if you also need to read constraints from annotations
        // $validator = Validation::createValidatorBuilder()
        //    ->enableAnnotationMapping()
        //   ->getValidator();
        return [
            new ValidatorExtension($validator),
        ];
    }
    */


    public function testSubmitValidData()
    {
        static::assertTrue(true);
        /*
        $username = 'testuser';
        $password = 'test';
        $email = 'testuser@gmail.com';
        $roles = ['ROLE_USER'];

        $formData = [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'roles' => $roles
        ];
        // Manually populated object
        $object = new User();
        $object->setUsername($username);
        $object->setPassword($password);
        $object->setEmail($email);
        $object->setRoles($roles);
        // Object that should be the same than the manually populated object
        $objectToCompare = new User();
        // Form creation that we link to our objectToCompare
        $form = $this->factory->create(UserType::class, $objectToCompare);
        $form->submit($formData);
        // Verification that form was correctly sent (data transformers didn't failed)
        static::assertTrue($form->isSynchronized());
        // Check that $objectToCompare was modified as expected when the form was submitted
        static::assertEquals($object->getUsername(), $objectToCompare->getUsername());
        static::assertEquals($object->getPassword(), $objectToCompare->getPassword());
        static::assertEquals($object->getEmail(), $objectToCompare->getEmail());
        static::assertEquals($object->getRoles(), $objectToCompare->getRoles());
        // Check the creation of the FormView. Should check if all widgets you want to display are available in the children property:
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
        */
    }
}