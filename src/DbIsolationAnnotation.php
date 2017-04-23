<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

/**
 * Searches @dbIsolationPerTest and @nestTransactionsWithSavepoints annotations.
 */
class DbIsolationAnnotation
{
    use AnnotationReaderTrait;

    const DB_ISOLATION = 'dbIsolation';

    /**
     * Use to avoid transaction rollbacks with Connection::transactional and missing on conflict in Doctrine
     * SQLSTATE[25P02] current transaction is aborted, commands ignored until end of transaction block
     */
    const NEST_TRANSACTIONS_WITH_SAVEPOINTS = 'nestTransactionsWithSavepoints';

    /**
     * @var bool[]
     */
    private static $dbIsolationPerTest;

    /**
     * @var bool[]
     */
    private static $nestTransactionsWithSavepoints = [];

    /**
     * Get value of dbIsolationPerTest option from annotation of called class
     * @param string|object $class
     * @return bool
     */
    public function hasDbIsolationSetting($class)
    {
        $fqcn = self::getFqcn($class);
        if (!isset(self::$dbIsolationPerTest[$fqcn])) {
            self::$dbIsolationPerTest[$fqcn] = self::hasClassAnnotation(
                $fqcn,
                static::getDbIsolationPerTestAnnotationName()
            );
        }

        return self::$dbIsolationPerTest[$fqcn];
    }

    /**
     * @param string|object $class
     * @return bool
     */
    public function hasNestTransactionsWithSavepoints($class)
    {
        $fqcn = self::getFqcn($class);
        if (!isset(self::$nestTransactionsWithSavepoints[$fqcn])) {
            self::$nestTransactionsWithSavepoints[$fqcn] =
                self::hasClassAnnotation($fqcn, static::getNestTransactionsWithSavepointsAnnotationName());
        }

        return self::$nestTransactionsWithSavepoints[$fqcn];
    }

    /**
     * @return string
     */
    protected static function getDbIsolationPerTestAnnotationName()
    {
        return static::DB_ISOLATION;
    }


    protected static function getNestTransactionsWithSavepointsAnnotationName()
    {
        return static::NEST_TRANSACTIONS_WITH_SAVEPOINTS;
    }

    private static function getFqcn($class)
    {
        if (is_object($class)) {
            $fqcn = get_class($class);
        } elseif (is_string($class) && class_exists($class)) {
            $fqcn = $class;
        } else {
            throw new \InvalidArgumentException(sprintf('Cannot load class: "%s"', $class));
        }

        return $fqcn;
    }
}
