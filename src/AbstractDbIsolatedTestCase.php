<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Use-case if you're going to extend WebTestCase.
 */
abstract class AbstractDbIsolatedTestCase extends WebTestCase
{
    use ClientTrait;

    /**
     * @var DbIsolationHandler
     */
    protected static $dbIsolationHandler;

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
        self::$dbIsolationHandler = new DbIsolationHandler(new DbIsolation(), new DbIsolationAnnotation());
    }

    /**
     * @before
     */
    protected function beforeTest()
    {
        $this->initClient(...$this->getClientArgs());
        self::$dbIsolationHandler->beforeTest(static::class, $this->client->getContainer()->get('doctrine'));
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
        self::$dbIsolationHandler->afterTest($this);
        static::ensureKernelShutdown();
    }

    abstract protected function getClientArgs();
}
