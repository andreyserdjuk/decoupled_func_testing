<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation;
use PHPUnit\Framework\TestCase;

/**
 * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation
 * @dbIsolation
 * @nestTransactionsWithSavepoints
 */
class DbIsolationAnnotationTest extends TestCase
{
    /**
     * @var DbIsolationAnnotation
     */
    protected static $isolationConfig;

    protected function setUp()
    {
        self::$isolationConfig = new DbIsolationAnnotation();
    }

    /**
     * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation::hasDbIsolationSetting()
     */
    public function testHasDbIsolationSetting()
    {
        $this->assertTrue(self::$isolationConfig->hasDbIsolationSetting($this));
        $this->assertFalse(self::$isolationConfig->hasDbIsolationSetting(new \stdClass()));
    }

    /**
     * @covers \AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation::hasNestTransactionsWithSavepoints()
     */
    public function testHasNestTransactionsWithSavepoints()
    {
        $this->assertTrue(self::$isolationConfig->hasNestTransactionsWithSavepoints($this));
        $this->assertFalse(self::$isolationConfig->hasNestTransactionsWithSavepoints(new \stdClass()));
    }
}
