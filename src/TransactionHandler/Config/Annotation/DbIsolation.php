<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandler\Config\Annotation;

use Doctrine\Common\Annotations\Annotation\Required;
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
    public $nestedSavepoints = false;

    /**
     * @return bool
     */
    public function hasNestedSavepoints()
    {
        return $this->nestedSavepoints;
    }
}
