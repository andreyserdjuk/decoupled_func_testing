<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandler\Config\Annotation;

/**
 * Base DbIsolation configuration interface
 */
interface ConfigurationInterface
{
    /**
     * @return bool
     */
    public function hasNestedSavepoints();
}
