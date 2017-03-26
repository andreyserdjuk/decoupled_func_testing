<?php

namespace AndreySerdjuk\DecoupledFuncTesting;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * {@inheritDoc}
 */
class ExampleTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    private static $clientInstance;

    /**
     * @var DbIsolationHandler
     */
    private static $dbIsolationHandler;

    public function setUp()
    {
        $this->initClient();

        self::$dbIsolationHandler = new DbIsolationHandler(new DbIsolation(), new DbIsolationAnnotation());
        self::$dbIsolationHandler->setUp(self::class, self::$clientInstance->getContainer()->get('doctrine'));
    }

    /**
     * Creates a Client.
     * As we can set db isolation per class - Client instance can be reused just with another server params.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $serverParams An array of server parameters
     *
     * @return Client A Client instance
     */
    protected function initClient(array $options = [], array $serverParams = [])
    {
        if (!self::$clientInstance) {
            self::$clientInstance = static::createClient($options, $serverParams);
        } else {
            self::$clientInstance->setServerParameters($serverParams);
        }

        return $this->client = self::$clientInstance;
    }
}
