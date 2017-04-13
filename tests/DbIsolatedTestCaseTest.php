<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\ClientTrait;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolatedTestCase;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationHandler;
use AndreySerdjuk\DecoupledFuncTesting\DbUtil;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolationPerTest
 */
class DbIsolatedTestCaseTest extends DbIsolatedTestCase
{
    use CustomKernelTrait;

    /**
     * Shared connection when a TestCase is run alone (outside of its functional suite).
     *
     * @var \Doctrine\DBAL\Connection|null
     */
    private static $sharedConn;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $conn;

    /**
     * @return void
     */
    protected function setUp()
    {
        if (!isset(self::$sharedConn)) {
            self::$sharedConn = DbUtil::getConnection();
        }

        $this->conn = self::$sharedConn;

        $schemaManager = $this->conn->getSchemaManager();
        $table = $schemaManager->createSchema()->createTable('test');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->setPrimaryKey(['id']);

        parent::setUp();
    }

    /**
     * Make sure that transaction was really started in setUp().
     */
    public function testRealTransaction()
    {
        /** @var Connection $conn */
        $conn = $this->client->getContainer()->get('doctrine')->getConnection();

        $conn->getParams();
    }

    protected function getClientArgs()
    {
        return [
            [
                'test_case' => 'DbIsolation',
                'root_config' => 'config.yml',
            ],
        ];
    }

    protected function resetSharedConn()
    {
        if (self::$sharedConn) {
            self::$sharedConn->close();
            self::$sharedConn = null;
        }
    }
}
