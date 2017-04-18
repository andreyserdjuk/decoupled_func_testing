<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * {@inheritDoc}
 */
abstract class DbIsolatedTestCase extends WebTestCase
{
    use ClientTrait;

    /**
     * @var DbIsolationHandler
     */
    protected static $dbIsolationHandler;

    protected function setUp()
    {
        $this->initClient(...$this->getClientArgs());
    }

    /**
     * @before
     */
    protected function beforeTest()
    {
        self::$dbIsolationHandler = new DbIsolationHandler(new DbIsolation(), new DbIsolationAnnotation());
        self::$dbIsolationHandler->setUp(static::class, $this->client->getContainer()->get('doctrine'));
    }

    /**
     * Disable kernel shutdown in \Symfony\Bundle\FrameworkBundle\Test\KernelTestCase::tearDown()
     */
    protected function tearDown()
    {
    }

    /**
     * @after
     */
    protected function afterTest()
    {
        self::$dbIsolationHandler->tearDown($this);
        static::ensureKernelShutdown();
    }

    /**
     * @setUpBeforeClass
     */
    public static function beforeClass()
    {
        /**
         *  As data provider in phpunit called before tests (even before @setUpBeforeClass) and can start a client
         *  (if we call $this->initClient() there) so we will have client without transaction started in our test.
         */
        self::resetClient();
    }

    /**
     * @tearDownAfterClass
     */
    public function afterClass()
    {
        self::$dbIsolationHandler->tearDownAfterClass($this);
    }

    abstract protected function getClientArgs();
}
