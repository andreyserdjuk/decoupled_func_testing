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
        $table = $schemaManager->createSchema()->createTable(
            $this->client->getContainer()->getParameter('db_name')
        );
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    /**
     * Make sure that transaction was really started in setUp().
     */
    public function testRealTransaction()
    {
        /** @var Connection $conn */
        $conn = $this->client->getContainer()->get('doctrine')->getConnection();

        $container = $this->client->getContainer();

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

    protected function tearDown()
    {
        $fs = new Filesystem();
        $fs->remove($this->cacheDir);

        parent::tearDown();
    }
}
