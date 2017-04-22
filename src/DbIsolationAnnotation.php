<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

/**
 * Searches @dbIsolation, @dbIsolationPerTest and @nestTransactionsWithSavepoints annotations.
 */
class DbIsolationAnnotation
{
    /**
     * @var bool[]
     */
    private static $dbIsolation;

    /**
     * @var bool[]
     */
    private static $dbIsolationPerTest;

    /**
     * @var bool[]
     */
    private static $nestTransactionsWithSavepoints = [];

    /**
     * Get value of dbIsolation option from annotation of called class
     * @param string|object $class
     * @return bool
     */
    public function getDbIsolationClassSetting($class)
    {
        $fqcn = self::getFqcn($class);
        if (!isset(self::$dbIsolation[$fqcn])) {
            self::$dbIsolation[$fqcn] = self::hasClassAnnotation($fqcn, static::getDbIsolationAnnotationName());
        }

        return self::$dbIsolation[$fqcn];
    }

    /**
     * Get value of dbIsolationPerTest option from annotation of called class
     * @param string|object $class
     * @return bool
     */
    public function getDbIsolationPerTestSetting($class)
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
    protected static function getDbIsolationAnnotationName()
    {
        return 'dbIsolation';
    }

    /**
     * @return string
     */
    protected static function getDbIsolationPerTestAnnotationName()
    {
        return 'dbIsolationPerTest';
    }

    /**
     * Use to avoid transaction rollbacks with Connection::transactional and missing on conflict in Doctrine
     * SQLSTATE[25P02] current transaction is aborted, commands ignored until end of transaction block
     */
    protected static function getNestTransactionsWithSavepointsAnnotationName()
    {
        return 'nestTransactionsWithSavepoints';
    }

    private static function getFqcn($class)
    {
        if (is_object($class)) {
            $fqcn = get_class($class);
        } elseif (is_string($class) && class_exists($class)) {
            $fqcn = $class;
        } else {
            throw new \InvalidArgumentException();
        }

        return $fqcn;
    }


    /**
     * @param string $className
     * @param string $annotationName
     *
     * @return bool
     */
    private static function hasClassAnnotation($className, $annotationName)
    {
        $annotations = \PHPUnit_Util_Test::parseTestMethodAnnotations($className);

        return isset($annotations['class'][$annotationName]);
    }
}
