<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Starts and rollbacks transactions in all connections if class has @dbIsolation annotation.
 */
class DbIsolationHandler
{
    /**
     * @var DbIsolation
     */
    protected static $dbIsolation;

    /**
     * @var DbIsolationAnnotation
     */
    protected static $isolationConfig;

    /**
     * @param DbIsolation           $dbIsolation
     * @param DbIsolationAnnotation $isolationConfig
     */
    public function __construct(DbIsolation $dbIsolation, DbIsolationAnnotation $isolationConfig)
    {
        self::$dbIsolation = $dbIsolation;
        self::$isolationConfig = $isolationConfig;
    }

    /**
     * @param string|object     $class
     * @param RegistryInterface $managerRegistry
     */
    public function beforeTest($class, RegistryInterface $managerRegistry)
    {
        if (self::$isolationConfig->hasDbIsolationSetting($class)) {
            self::$dbIsolation->startTransaction(
                $managerRegistry,
                self::$isolationConfig->hasNestTransactionsWithSavepoints($class)
            );
        }
    }

    /**
     * @param string|object $class
     */
    public function afterTest($class)
    {
        if (self::$isolationConfig->hasDbIsolationSetting($class)) {
            self::$dbIsolation->rollbackTransaction();
        }
    }
}
