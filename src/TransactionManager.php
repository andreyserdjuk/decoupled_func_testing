<?php

namespace AndreySerdjuk\DbIsolation;

use AndreySerdjuk\DbIsolation\TransactionHandler\TransactionManagerInterface;
use Doctrine\DBAL\Connection;

/**
 * Starts and rollbacks transactions in all connections.
 */
class TransactionManager implements TransactionManagerInterface
{
    /**
     * @var Connection[]
     */
    protected static $connections = [];

    /**
     * Start transaction in each connection.
     * @param Connection[] $connections
     * @param bool         $nestSavepoints nest transactions with savepoints
     */
    public function startTransaction(array $connections, $nestSavepoints = false)
    {
        foreach ($connections as $connection) {
            if ($connection->getNestTransactionsWithSavepoints() !== $nestSavepoints) {
                $connection->setNestTransactionsWithSavepoints($nestSavepoints);
            }
            $connection->beginTransaction();

            self::$connections[] = $connection;
        }
    }

    /**
     * Rollback all active transactions in all active connections.
     */
    public function rollbackTransaction()
    {
        foreach (self::$connections as $connection) {
            while ($connection->isConnected() && $connection->isTransactionActive()) {
                $connection->rollBack();
            }
        }

        self::$connections = [];
    }
}
