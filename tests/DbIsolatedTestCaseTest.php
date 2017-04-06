<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\ClientTrait;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolatedTestCase;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationHandler;
use Doctrine\Tests\DbUtil;
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
    }

    public function testRealTransaction()
    {
        $conn = $this->client->getContainer()->get('doctrine')->getConnection();

    }

    protected function getClientArgs()
    {
        return [
            'test_case' => 'DbIsolation',
            'root_config' => 'config.yml',
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
