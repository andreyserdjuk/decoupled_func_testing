<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation
 * @dbIsolationPerTest
 * @dbIsolation
 * @nestTransactionsWithSavepoints
 */
class DbIsolationAnnotationTest extends TestCase
{
    /**
     * @var DbIsolationAnnotation
     */
    protected static $dbIsolationAnnotation;

    protected function setUp()
    {
        self::$dbIsolationAnnotation = new DbIsolationAnnotation();
    }

    /**
     * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation::getDbIsolationPerTestSetting()
     */
    public function testHasDbIsolationPerTestSetting()
    {
        $this->assertTrue(self::$dbIsolationAnnotation->getDbIsolationPerTestSetting($this));
        $this->assertFalse(self::$dbIsolationAnnotation->getDbIsolationPerTestSetting(new \stdClass()));
    }

    /**
     * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation::getDbIsolationPerClassSetting()
     */
    public function testHasDbIsolationPerClassSetting()
    {
        $this->assertTrue(self::$dbIsolationAnnotation->getDbIsolationClassSetting($this));
        $this->assertFalse(self::$dbIsolationAnnotation->getDbIsolationClassSetting(new \stdClass()));
    }

    /**
     * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation::hasNestTransactionsWithSavepoints()
     */
    public function testHasNestTransactionsWithSavepoints()
    {
        $this->assertTrue(self::$dbIsolationAnnotation->hasNestTransactionsWithSavepoints($this));
        $this->assertFalse(self::$dbIsolationAnnotation->hasNestTransactionsWithSavepoints(new \stdClass()));
    }
}
