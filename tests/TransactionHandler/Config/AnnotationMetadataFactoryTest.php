<?php

namespace Tests\AndreySerdjuk\DbIsolation\TransactionManager\Config;

use AndreySerdjuk\DbIsolation\TransactionHandler\Config\Annotation\DbIsolation;
use AndreySerdjuk\DbIsolation\TransactionHandler\Config\AnnotationMetadataFactory;
use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use PHPUnit\Framework\TestCase;

/**
 * @DbIsolation(nestedSavepoints=true)
 */
class AnnotationMetadataFactoryTest extends TestCase
{
    /**
     * @var AnnotationMetadataFactory
     */
    private $metadataFactory;

    public function testReadMetadata()
    {
        /** @var DbIsolation $metadata */
        $metadata = $this->metadataFactory->getMetadata($this);

        $this->assertInstanceOf(DbIsolation::class, $metadata);
        $this->assertTrue($metadata->hasNestedSavepoints());
    }

    public function testNoAnotation()
    {
        $metadata = $this->metadataFactory->getMetadata(new \stdClass());

        $this->assertNull($metadata);
    }

    public function testWrongAnnotation()
    {
        $this->expectException(AnnotationException::class);
        $this->metadataFactory->getMetadata(new BuggyAnnotation());
    }

    /**
     * @before
     */
    protected function initMetadataFactory()
    {
        $this->metadataFactory = new AnnotationMetadataFactory(new AnnotationReader());
    }
}

/**
 * @DbIsolation(nestedSavepoints=123)
 */
class BuggyAnnotation {
}
