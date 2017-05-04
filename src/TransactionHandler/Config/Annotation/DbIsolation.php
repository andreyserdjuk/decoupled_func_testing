<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandlers\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
class DbIsolation implements ConfigurationInterface
{
    /**
     * Use to avoid transaction rollbacks with Connection::transactional and missing on conflict in Doctrine
     * SQLSTATE[25P02] current transaction is aborted, commands ignored until end of transaction block
     *
     * @var bool
     */
    private $nestedSavepoints;

    /**
     * Annotation constructor.
     * @param bool $nestedSavepoints
     */
    public function __construct($nestedSavepoints)
    {
        $this->nestedSavepoints = $nestedSavepoints;
    }

    /**
     * @return bool
     */
    public function hasNestedSavepoints()
    {
        return $this->nestedSavepoints;
    }
}
