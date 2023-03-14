<?php
declare(strict_types=1);

namespace App\Tests;

use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use LogicException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TestCase extends WebTestCase
{
    protected ?EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $kernel = $this->client->getKernel();

        if ('test' !== $kernel->getEnvironment()) {
            throw new LogicException('Execution only in Test environment possible!');
        }
        $this->initDatabase($kernel);

        $container = static::getContainer();

        $this->entityManager = $container
            ->get('doctrine')
            ->getManager();

        $this->validator = $container->get(ValidatorInterface::class);
    }

    private function initDatabase(KernelInterface $kernel): void
    {
        $entityManager = $kernel->getContainer()->get('doctrine.orm.entity_manager');
        $metaData = $entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($entityManager);
        $schemaTool->updateSchema($metaData);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function client(): AbstractBrowser
    {
        return $this->client;
    }

    public function validate(object $entity): ConstraintViolationListInterface
    {
        return $this->validator->validate($entity);
    }

    public function carRepository(): CarRepository
    {
        return self::getContainer()->get(CarRepository::class);
    }
}