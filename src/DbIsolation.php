<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DbIsolation
{
    /**
     * @var Connection[]
     */
    protected static $dbIsolationConnections = [];

    public function startTransaction(RegistryInterface $registry, $nestTransactionsWithSavepoints = false)
    {
        foreach ($registry->getManagers() as $name => $em) {
            if ($em instanceof EntityManagerInterface) {
                $em->clear();
                $connection = $em->getConnection();
                if ($connection->getNestTransactionsWithSavepoints() !== $nestTransactionsWithSavepoints) {
                    $connection->setNestTransactionsWithSavepoints($nestTransactionsWithSavepoints);
                }
                $connection->beginTransaction();

                self::$dbIsolationConnections[$name.uniqid('connection', true)] = $connection;
            }
        }
    }

    public function rollbackTransaction()
    {
        foreach (self::$dbIsolationConnections as $name => $connection) {
            while ($connection->isConnected() && $connection->isTransactionActive()) {
                $connection->rollBack();
            }
        }

        self::$dbIsolationConnections = [];
    }
}
