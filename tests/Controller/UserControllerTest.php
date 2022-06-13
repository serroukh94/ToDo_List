<?php

namespace App\Tests\Controller;

use App\Tests\DataFixtures\DataFixtureTestCase;


class UserControllerTest extends DataFixtureTestCase
{
    public function testListActionWithoutLogin(): void
    {
        // If the user isn't logged, should redirect to the login page
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request('GET', '/users');
        static::assertSame(302, $client->getResponse()->getStatusCode());

        $crawler = $client->followRedirect();
        // Test if login field exists
        static::assertSame(1, $crawler->filter('input[name="_username"]')->count());
        static::assertSame(1, $crawler->filter('input[name="_password"]')->count());
    }

    public function testListAction(): void
    {
        $securityController = new SecurityControllerTest();
        $client = $securityController->testLoginAsAdmin();

        $crawler = $client->request('GET', '/users');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }





    public function testDeleteAction(): void
    {
        $securityController = new SecurityControllerTest();
        $client = $securityController->testLoginAsAdmin();

        $crawler = $client->request('DELETE', '/users');
        static::assertSame(200, $client->getResponse()->getStatusCode());
        static::assertSame("Liste des utilisateurs", $crawler->filter('h1')->text());
    }
}