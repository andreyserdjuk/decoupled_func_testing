<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Starts and rollbacks transactions in all connections.
 */
class DbIsolation
{
    /**
     * @var Connection[]
     */
    protected static $connections = [];

    /**
     * Start transaction in each connection.
     * @param RegistryInterface $registry
     * @param bool              $nestSavepoints nest transactions with savepoints
     */
    public function startTransaction(RegistryInterface $registry, $nestSavepoints = false)
    {
        foreach ($registry->getManagers() as $name => $em) {
            if ($em instanceof EntityManagerInterface) {
                $em->clear();
                $connection = $em->getConnection();
                if ($connection->getNestTransactionsWithSavepoints() !== $nestSavepoints) {
                    $connection->setNestTransactionsWithSavepoints($nestSavepoints);
                }
                $connection->beginTransaction();

                self::$connections[$name.uniqid('connection', true)] = $connection;
            }
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
