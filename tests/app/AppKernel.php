<?php

namespace Tests\AndreySerdjuk\app;

require_once __DIR__ . '/bootstrap.php';

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * Directory name, when bundles.php placed in ./ directory.
     *
     * @var string
     */
    private $testCase;

    /**
     * Path to config.yml placed in self::$testCase directory.
     *
     * @var string
     */
    private $rootConfig;

    public function __construct($testCase, $rootConfig, $environment, $debug)
    {
        if (!is_dir(__DIR__.'/'.$testCase)) {
            throw new \InvalidArgumentException(sprintf('The test case "%s" does not exist.', $testCase));
        }
        $this->testCase = $testCase;

        $fs = new Filesystem();
        if (!$fs->isAbsolutePath($rootConfig) && !file_exists($rootConfig = __DIR__.'/'.$testCase.'/'.$rootConfig)) {
            throw new \InvalidArgumentException(sprintf('The root config "%s" does not exist.', $rootConfig));
        }
        $this->rootConfig = $rootConfig;

        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        if (!file_exists($filename = $this->getRootDir().'/'.$this->testCase.'/bundles.php')) {
            throw new \RuntimeException(sprintf('The bundles file "%s" does not exist.', $filename));
        }

        return include $filename;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$this->testCase.'/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/'.$this->testCase.'/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->setParameter('db_type', $GLOBALS['db_type']);
            $container->setParameter('db_host', $GLOBALS['db_host']);
            $container->setParameter('db_port', $GLOBALS['db_port']);
            $container->setParameter('db_name', $GLOBALS['db_name']);
            $container->setParameter('db_user', $GLOBALS['db_username']);
            $container->setParameter('db_password', $GLOBALS['db_password']);
        });

        $loader->load($this->rootConfig);
    }

    public function serialize()
    {
        return serialize(array($this->testCase, $this->rootConfig, $this->getEnvironment(), $this->isDebug()));
    }

    public function unserialize($str)
    {
        $a = unserialize($str);
        $this->__construct($a[0], $a[1], $a[2], $a[3]);
    }

    protected function getKernelParameters()
    {
        $parameters = parent::getKernelParameters();
        $parameters['kernel.test_case'] = $this->testCase;

        return $parameters;
    }
}
