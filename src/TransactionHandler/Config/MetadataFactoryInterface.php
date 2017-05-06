<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandler\Config;

use AndreySerdjuk\DbIsolation\TransactionHandler\Config\Annotation\ConfigurationInterface;

/**
 * Interface for all possible metadata factories - configuration providers.
 * I assume that annotation is the only place for DbIsolation config but someone can hate annotations.
 */
interface MetadataFactoryInterface
{
    /**
     * @param string|object $class
     * @return ConfigurationInterface
     */
    public function getMetadata($class);
}
