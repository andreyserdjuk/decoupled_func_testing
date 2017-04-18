<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\DbIsolatedTestCase;
use AndreySerdjuk\DecoupledFuncTesting\DbUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\Filesystem\Filesystem;

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

    protected $cacheDir;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cacheDir = $this->client->getKernel()->getCacheDir();

        if (!isset(self::$sharedConn)) {
            self::$sharedConn = DbUtil::getConnection();
        }

        $this->conn = self::$sharedConn;

        $schemaManager = $this->conn->getSchemaManager();
        $table = $schemaManager->createSchema()->createTable('test');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->setPrimaryKey(['id']);
        $schemaManager->createTable($table);
    }

    /**
     * Make sure that transaction was really started in setUp().
     */
    public function testRealTransaction()
    {
        /** @var Connection $conn */
        $conn = $this->client->getContainer()->get('doctrine')->getConnection();

        $conn->insert('test', [
            'id' => 1,
            'name' => 'hi',
        ]);

        $res = $conn->fetchAssoc('SELECT * FROM test');

        $this->assertArrayHasKey('id', $res);
        $this->assertArrayHasKey('name', $res);
        $this->assertEquals(1, $res['id']);
        $this->assertEquals('hi', $res['name']);
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

    /**
     * @after
     */
    protected function afterTest()
    {
        self::$dbIsolationHandler->tearDown($this);

        /** @var Connection $conn */
        $conn = $this->client->getContainer()->get('doctrine')->getConnection();
        $res = $conn->fetchAssoc('SELECT * FROM test');

        static::ensureKernelShutdown();

        $fs = new Filesystem();
        $fs->remove($this->cacheDir);

        $this->assertEmpty($res);
    }
}
