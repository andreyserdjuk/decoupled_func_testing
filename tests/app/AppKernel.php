<?php
/**
 * Created by IntelliJ IDEA.
 * User: andreyserdjuk
 * Date: 3/28/17
 * Time: 12:58 AM
 */

namespace Tests\AndreySerdjuk\app;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
        );

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/nelmio-api-doc/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/nelmio-api-doc/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/'.$this->environment.'.yml');

        if (class_exists('Dunglas\ApiBundle\DunglasApiBundle')) {
            $loader->load(__DIR__.'/config/dunglas_api.yml');
        }

        // If symfony/framework-bundle > 3.0
        if (!class_exists('Symfony\Bundle\FrameworkBundle\Command\RouterApacheDumperCommand')) {
            $loader->load(__DIR__.'/config/twig_assets.yml');
        }
    }

    public function serialize()
    {
        return serialize(array($this->getEnvironment(), $this->isDebug()));
    }

    public function unserialize($str)
    {
        call_user_func_array(array($this, '__construct'), unserialize($str));
    }
}
