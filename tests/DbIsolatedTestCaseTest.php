<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\ClientTrait;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolationPerTest
 */
class DbIsolatedTestCaseTest extends WebTestCase
{
    use ClientTrait;
    use CustomKernelTrait;

    /**
     * @var DbIsolationHandler
     */
    private static $dbIsolationHandler;

    public function testRealTransaction()
    {
        $this->client->getContainer()->get('doctrine')->getConnection();
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
        self::$dbIsolationHandler->tearDownAfterClass(self::class);
    }

    protected function setUp()
    {
        $this->initClient([
            'test_case' => 'DbIsolation',
            'root_config' => 'config.yml',
        ]);

        self::$dbIsolationHandler = new DbIsolationHandler(new DbIsolation(), new DbIsolationAnnotation());
        self::$dbIsolationHandler->setUp(self::class, $this->client->getContainer()->get('doctrine'));
    }

    protected function tearDown()
    {
        self::$dbIsolationHandler->tearDown(self::class);
        parent::tearDown();
    }
}
