<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testGetId(): void
    {
        $user = new User();
        static::assertEquals(null, $user->getId());
    }

    public function testGetSetUsername(): void
    {
        $user = new User();
        $user->setUsername('Test username');
        static::assertEquals('Test username', $user->getUsername());
    }

    public function testGetSetPassword(): void
    {
        $user = new User();
        $user->setPassword('Test password');
        static::assertEquals('Test password', $user->getPassword());
    }

    public function testGetSetEmail(): void
    {
        $user = new User();
        $user->setEmail('Test@email.com');
        static::assertEquals('Test@email.com', $user->getEmail());
    }

    public function testGetSalt(): void
    {
        $user = new User();
        static::assertEquals(null, $user->getSalt());
    }

    public function testGetAddTask(): void
    {
        $user = new User();
        static::assertInstanceOf(User::class, $user->AddTask(new Task()));
        static::assertInstanceOf(ArrayCollection::class, $user->getTasks());
        static::assertContainsOnlyInstancesOf(Task::class, $user->getTasks());
    }

    public function testRemoveTask(): void
    {
        $user = new User();
        // If there is not the Task in the ArrayCollection
        static::assertInstanceOf(User::class, $user->removeTask(new Task()));
        static::assertEmpty($user->getTasks());

        // If there is the Task in the ArrayCollection
        $task = new Task();
        $user->addTask($task);
        $user->removeTask($task);
        static::assertEmpty($user->getTasks());
        static::assertInstanceOf(User::class, $user->removeTask(new Task()));
    }

    public function testGetSetRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);
        static::assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testEraseCredentials(): void
    {
        $user = new User();
        static::assertEquals(null, $user->eraseCredentials());
    }
}