<?php

namespace AndreySerdjuk\DbIsolation;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Use-case if you're going to extend WebTestCase.
 */
abstract class AbstractDbIsolatedTestCase extends WebTestCase
{
    use ClientTrait;

    use ClientDbIsolationTrait;
}
