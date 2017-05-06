<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandler;

use Doctrine\DBAL\Connection;

/**
 * Interface for TransactionHandler
 */
interface HandlerInterface
{
    /**
     * @param string|object $class
     * @param Connection[]  $connections
     */
    public function beforeDbChanges($class, array $connections);

    /**
     * @param string|object $class
     */
    public function afterDbChanges($class);
}
