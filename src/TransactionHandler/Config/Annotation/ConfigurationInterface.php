<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandlers\Annotation;

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
