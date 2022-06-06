<?php

namespace App\DataFixtures\Test;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Id\IdentityGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{

    public static function getGroups(): array
    {
        return ['test'];
    }

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        // Anonymous User
        $user = new User();
        $user->setId(-1)
            ->setUsername('anonymous')
            ->setEmail('anonymous@example.org')
            ->setPassword($this->encoder->encodePassword($user, 'test'))
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user);
        $this->addReference('user-anonymous', $user);

        // Admin User
        $user = new User();
        $user->setId(1)
            ->setUsername('admin')
            ->setEmail('admin@example.org')
            ->setPassword($this->encoder->encodePassword($user, 'test'))
            ->setRoles(['ROLE_ADMIN'])
        ;
        $manager->persist($user);
        $this->addReference('user-admin', $user);

        // Simple User
        $user = new User();
        $user->setId(2)
            ->setUsername('user')
            ->setEmail('user@example.org')
            ->setPassword($this->encoder->encodePassword($user, 'test'))
            ->setRoles(['ROLE_USER'])
        ;
        $manager->persist($user);
        $this->addReference('user-simple', $user);

        // Tâche crée par utilisateur simple (Seul auteur peut la delete)
        $task = new Task();
        $task->setId(1) // Edition par utilisateur simple
        ->setTitle('Test utilisateur simple')
            ->setContent('Tâche utilisé pour les tests')
            ->setAuthor($this->getReference('user-simple'))
        ;
        $manager->persist($task);

        // Tâche crée par utilisateur simple (Seul auteur peut la delete)
        $task = new Task();
        $task->setId(2) // Suppression par utilisateur simple qui est l'auteur
        ->setTitle('Test utilisateur simple')
            ->setContent('Tâche utilisé pour les tests')
            ->setAuthor($this->getReference('user-simple'))
        ;
        $manager->persist($task);

        // Tâche créer par utilisateur anonyme (Seul admin peut la delete)
        $task = new Task();
        $task->setId(3) // Suppression par utilisateur simple (Doit être refusé)
        ->setTitle('Test utilisateur anonyme')
            ->setContent('Tâche utilisé pour les tests')
            ->setAuthor($this->getReference('user-anonymous'))
        ;
        $manager->persist($task);

        // Tâche créer par admin (Seul l'auteur peut la delete)
        $task = new Task();
        $task->setId(4)
            ->setTitle('Test admin')
            ->setContent('Tâche utilisé pour les tests')
            ->setAuthor($this->getReference('user-admin'))
        ;
        $manager->persist($task);

        // Desactive l'autoincrement des id
        $metadata = $manager->getClassMetadata(Task::class);
        $metadata->setIdGenerator(new AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);

        $metadata = $manager->getClassMetadata(User::class);
        $metadata->setIdGenerator(new AssignedGenerator());
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);

        $manager->flush();

        // Reactive l'autoincrement des id pour que les actions de creation fonctionnent
        $metadata = $manager->getClassMetadata(Task::class);
        $metadata->setIdGenerator(new IdentityGenerator());
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_IDENTITY);

        $metadata = $manager->getClassMetadata(User::class);
        $metadata->setIdGenerator(new IdentityGenerator());
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_IDENTITY);
    }
}