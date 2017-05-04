<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandlers;

use Doctrine\DBAL\Connection;

/**
 * Get connections from registry and start transaction for all of them
 */
interface TransactionManagerInterface
{
    /**
     * Start transaction in each connection.
     * @param Connection[] $connections
     * @param bool         $nestSavepoints nest transactions with savepoints
     */
    public function startTransaction(array $connections, $nestSavepoints = false);

    /**
     * Rollback all active transactions in all active connections.
     */
    public function rollbackTransaction();
}
