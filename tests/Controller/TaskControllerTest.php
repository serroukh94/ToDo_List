<?php

namespace App\Tests\Controller;

use App\Tests\DataFixtures\DataFixtureTestCase;


class TaskControllerTest extends DataFixtureTestCase
{
    public function testListActionWithoutLogin(): void
    {
        // If the user isn't logged, should redirect to the login page
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/tasks');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }

    public function testListAction(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $client->request('GET', '/tasks');
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateAction(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $crawler = $client->request('GET', '/tasks/create');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Ajouter')->form();
        $form['task[title]'] = 'Nouvelle tâche';
        $form['task[content]'] = 'Ceci est une tâche crée par un test';
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    // Edition d'une tâche par un utilisateur simple
    public function testEditAction(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $crawler = $client->request('GET', '/tasks/1/edit');
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if creation page field exists
        static::assertSame(1, $crawler->filter('input[name="task[title]"]')->count());
        static::assertSame(1, $crawler->filter('textarea[name="task[content]"]')->count());

        $form = $crawler->selectButton('Modifier')->form();
        $form['task[title]'] = 'Modification de tache';
        $form['task[content]'] = 'Je modifie une tache';
        $client->submit($form);
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }

    // Suppression d'une tâche crée par un utiliseur simple et supprimé par le même auteur
    public function testDeleteTaskAction(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $crawler = $client->request('GET', '/tasks/2/delete');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if success message is displayed
        static::assertStringContainsString("Superbe ! La tâche a bien été supprimée.",
            $crawler->filter('div.alert.alert-success')->text());
    }

    public function testDeleteTaskActionWhereSimpleUserIsNotAuthor(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $crawler = $client->request('GET', '/tasks/4/delete');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if success message is displayed
        static::assertStringContainsString("Oops ! Seul l'auteur de la tâche ou un admin peut la supprimer !",
            $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testDeleteTaskActionWithSimpleUserWhereAuthorIsAnonymous(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $crawler = $client->request('GET', '/tasks/3/delete');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());

        // Test if success message is displayed
        static::assertStringContainsString("Oops ! Seul un admin peut supprimer une tâche de l'utilisateur anonyme !",
            $crawler->filter('div.alert.alert-danger')->text());
    }

    public function testDeleteTaskActionWhereItemDontExists(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $client->request('GET', '/tasks/-100/delete');
        static::assertSame(404, $client->getResponse()->getStatusCode());
    }

    public function testToggleTaskAction(): void
    {
        $securityControllerTest = new SecurityControllerTest();
        $client = $securityControllerTest->testLogin();

        $client->request('GET', '/tasks/1/toggle');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        static::assertSame(200, $client->getResponse()->getStatusCode());
    }


}