<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandler;

use AndreySerdjuk\DbIsolation\TransactionHandler\Config\MetadataFactoryInterface;
use Doctrine\DBAL\Connection;

/**
 * {@inheritdoc}
 */
class TransactionHandler implements HandlerInterface
{
    /**
     * @var MetadataFactoryInterface
     */
    protected $metadataFactory;

    /**
     * @var TransactionManagerInterface
     */
    protected $transactionManager;

    /**
     * @param MetadataFactoryInterface    $metadataFactory
     * @param TransactionManagerInterface $transactionManager
     */
    public function __construct(MetadataFactoryInterface $metadataFactory, TransactionManagerInterface $transactionManager)
    {
        $this->metadataFactory = $metadataFactory;
        $this->transactionManager = $transactionManager;
    }

    /**
     * @param string|object $class
     * @param Connection[]  $connections
     */
    public function beforeDbChanges($class, array $connections)
    {
        if ($config = $this->metadataFactory->getMetadata($class)) {
            $this->transactionManager->startTransaction($connections, $config->hasNestedSavepoints());
        }
    }

    /**
     * @param string|object $class
     */
    public function afterDbChanges($class)
    {
        if ($this->metadataFactory->getMetadata($class)) {
            $this->transactionManager->rollbackTransaction();
        }
    }
}
