<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\DbIsolatedTestCase;
use AndreySerdjuk\DecoupledFuncTesting\DbUtil;
use Doctrine\DBAL\Connection;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @dbIsolationPerTest
 */
class DbIsolatedTestCasePerClassTest extends DbIsolatedTestCase
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
