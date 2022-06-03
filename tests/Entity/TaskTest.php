<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function testGetId(): void
    {
        $task = new Task();
        static::assertEquals(null, $task->getId());
    }

    public function testGetSetTitle(): void
    {
        $task = new Task();
        $task->setTitle('Test title');
        static::assertEquals('Test title', $task->getTitle());
    }

    public function testGetSetContent(): void
    {
        $task = new Task();
        $task->setContent('Test content');
        static::assertEquals('Test content', $task->getContent());
    }

    public function testGetSetCreatedAt(): void
    {
        $task = new Task();
        $task->setCreatedAt(new \DateTime);
        static::assertInstanceOf(\DateTime::class, $task->getCreatedAt());
    }

    public function testGetSetAuthor(): void
    {
        $task = new Task();
        $task->setAuthor(new User);
        static::assertInstanceOf(User::class, $task->getAuthor());
    }

    public function testIsDoneToggle(): void
    {
        $task = new Task();
        $task->toggle(true);
        static::assertEquals(true, $task->isDone());
    }
}