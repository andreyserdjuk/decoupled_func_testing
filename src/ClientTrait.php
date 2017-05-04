<?php

namespace AndreySerdjuk\DbIsolation;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait ClientTrait
{
    /**
     * @var Client
     */
    private static $clientInstance;

    /**
     * @var Client
     */
    protected $client;

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

    /**
     * Reset client and rollback transaction
     */
    protected static function resetClient()
    {
        if (self::$clientInstance) {
            self::$clientInstance = null;
        }

        static::ensureKernelShutdown();
    }

    /**
     * Get an instance of the dependency injection container.
     *
     * @return ContainerInterface
     */
    protected static function getContainer()
    {
        return static::getClientInstance()->getContainer();
    }

    /**
     * @return Client
     * @throws \BadMethodCallException
     */
    protected static function getClientInstance()
    {
        if (!self::$clientInstance) {
            throw new \BadMethodCallException('Client instance is not initialized.');
        }

        return self::$clientInstance;
    }

    /**
     * Creates a Client.
     *
     * @param array $options An array of options to pass to the createKernel class
     * @param array $server  An array of server parameters
     *
     * @return Client A Client instance
     */
    abstract protected function createClient(array $options = array(), array $server = array());

    /**
     * Shuts the kernel down if it was used in the test.
     */
    abstract protected function ensureKernelShutdown();
}
