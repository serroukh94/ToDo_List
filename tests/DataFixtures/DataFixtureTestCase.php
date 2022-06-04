<?php

namespace App\Tests\DataFixtures;

use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DataFixtureTestCase extends WebTestCase
{
    /** @var  Application $application */
    protected static $application;

    /** @var  KernelBrowser $client */
    protected $client;

    /** @var  ContainerInterface $container */
    protected $containerTest;

    /** @var  EntityManager $entityManager */
    protected $entityManager;

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function setUp(): void
    {
        self::runCommand('doctrine:database:drop --force');
        self::runCommand('doctrine:database:create');
        self::runCommand('doctrine:schema:create');
        self::runCommand('doctrine:fixtures:load --append --no-interaction --env=test --group=test');

        $this->client = static::createClient();
        $this->containerTest = $this->client->getContainer();
        $this->entityManager = $this->containerTest->get('doctrine.orm.entity_manager');

        parent::setUp();
    }

    /**
     * @throws Exception
     */
    protected static function runCommand($command)
    {
        $command = sprintf('%s --quiet', $command);

        return self::getApplication()->run(new StringInput($command));
    }

    protected static function getApplication(): Application
    {
        if (null === self::$application) {

            $kernel = static::createKernel();
            self::$application = new Application($kernel);
            self::$application->setAutoExit(false);
        }

        return self::$application;
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    protected function tearDown(): void
    {
        self::runCommand('doctrine:database:drop --force');

        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
}