<?php

namespace Tests\AndreySerdjuk\DecoupledFuncTesting;

use AndreySerdjuk\DecoupledFuncTesting\ClientTrait;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolatedTestCase;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationAnnotation;
use AndreySerdjuk\DecoupledFuncTesting\DbIsolationHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolationPerTest
 */
class DbIsolatedTestCaseTest extends DbIsolatedTestCase
{
    use CustomKernelTrait;

    public function testRealTransaction()
    {
        $conn = $this->client->getContainer()->get('doctrine')->getConnection();

    }

    protected function getClientArgs()
    {
        return [
            'test_case' => 'DbIsolation',
            'root_config' => 'config.yml',
        ];
    }
}
