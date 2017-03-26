<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Symfony\Bridge\Doctrine\RegistryInterface;

class DbIsolationHandler
{
    /**
     * @var DbIsolation
     */
    protected $dbIsolation;

    /**
     * @var DbIsolationAnnotation
     */
    protected $dbIsolationAnnotation;

    /**
     * @param DbIsolation $dbIsolation
     * @param DbIsolationAnnotation $dbIsolationAnnotation
     */
    public function __construct(DbIsolation $dbIsolation, DbIsolationAnnotation $dbIsolationAnnotation)
    {
        $this->dbIsolation = $dbIsolation;
        $this->dbIsolationAnnotation = $dbIsolationAnnotation;
    }

    public function setUp($class, RegistryInterface $managerRegistry)
    {
        if ($this->dbIsolationAnnotation->getDbIsolationPerTestSetting($class)) {
            $this->startTransaction($class, $managerRegistry);
        }
    }

    public function tearDown($class)
    {
        if ($this->dbIsolationAnnotation->getDbIsolationPerTestSetting($class)) {
            $this->dbIsolation->rollbackTransaction();
        }
    }

    public function setUpBeforeClass($class, RegistryInterface $managerRegistry)
    {
        if ($this->dbIsolationAnnotation->getDbIsolationClassSetting($class)) {
            $this->startTransaction($class, $managerRegistry);
        }
    }

    public function tearDownAfterClass($class)
    {
        if ($this->dbIsolationAnnotation->getDbIsolationClassSetting($class)) {
            $this->dbIsolation->rollbackTransaction();
        }
    }

    private function startTransaction($class, $managerRegistry)
    {
        $this->dbIsolation->startTransaction(
            $managerRegistry,
            $this->dbIsolationAnnotation->hasNestTransactionsWithSavepoints($class)
        );
    }
}
