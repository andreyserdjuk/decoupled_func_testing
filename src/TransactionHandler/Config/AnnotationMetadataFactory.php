<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandler\Config;

use AndreySerdjuk\DbIsolation\TransactionHandler\Config\Annotation\DbIsolation;
use Doctrine\Common\Annotations\Reader;

/**
 */
class AnnotationMetadataFactory implements MetadataFactoryInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    /**
     * AnnotationMetadataFactory constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param object|string $class
     * @return null|object|DbIsolation
     */
    public function getMetadata($class)
    {
        return $this
            ->reader
            ->getClassAnnotation(
                new \ReflectionClass($class),
                DbIsolation::class
            );
    }
}
