<?php

namespace AndreySerdjuk\DbIsolation\TransactionHandlers;

use AndreySerdjuk\DbIsolation\TransactionHandlers\Annotation\DbIsolation;
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
     * @return null|object
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
