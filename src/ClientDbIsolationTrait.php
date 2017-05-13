<?php

namespace AndreySerdjuk\DbIsolation;

use AndreySerdjuk\DbIsolation\TransactionHandler\Config\AnnotationMetadataFactory;
use AndreySerdjuk\DbIsolation\TransactionHandler\TransactionHandler;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait ClientDbIsolationTrait
{
    /**
     * @var TransactionHandler
     */
    protected static $transactionHandler;

    /**
     * The first call.
     */
    public static function setUpBeforeClass()
    {
        /**
         *  As data provider in phpunit called before tests (even before @setUpBeforeClass) and can start a client
         *  (if we call $this->initClient() there) so we will have client without transaction started in our test.
         */
        self::resetClient();
        self::$transactionHandler = new TransactionHandler(
            new AnnotationMetadataFactory(new AnnotationReader()),
            new TransactionManager()
        );
    }

    /**
     * @before
     */
    protected function beforeTest()
    {
        $this->initClient(...$this->getClientArgs());

        /** @var ContainerInterface $container */
        $container = static::getContainer();

        /** @var RegistryInterface $registry */
        $registry = $container->get('doctrine');

        foreach ($registry->getManagers() as $em) {
            if ($em instanceof EntityManagerInterface) {
                $em->clear();
            }
        }

        self::$transactionHandler->beforeDbChanges(static::class, $registry->getConnections());
    }

    /**
     * First call after test.
     * Disable kernel shutdown in \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase::tearDown()
     * in order to do it later.
     */
    protected function tearDown()
    {
    }

    /**
     * Seconds call after test.
     * @after
     */
    protected function afterTest()
    {
        self::$transactionHandler->afterDbChanges(static::class);
        static::ensureKernelShutdown();
    }

    abstract protected function getClientArgs();

    /**
     * Shuts the kernel down if it was used in the test.
     */
    abstract protected function ensureKernelShutdown();
}
