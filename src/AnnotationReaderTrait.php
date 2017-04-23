<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

/**
 * Reads annotations with PHPUnit.
 */
trait AnnotationReaderTrait
{
    /**
     * @param string $className
     * @param string $annotationName
     *
     * @return bool
     */
    public static function hasClassAnnotation($className, $annotationName)
    {
        $annotations = \PHPUnit_Util_Test::parseTestMethodAnnotations($className);

        return isset($annotations['class'][$annotationName]);
    }
}
