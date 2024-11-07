<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    private const string CONFIG_EXTS = '.{php,xml,yaml,yml}';

    //    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    //    {
    //        $confDir = $this->getProjectDir().'/config';
    //
    //        $loader->load($confDir.'/{services}/*'.self::CONFIG_EXTS, 'glob');
    //        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
    //        $loader->load($confDir.'/taxes/*'.self::CONFIG_EXTS, 'glob');
    //    }
    //
    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, 'glob');
        $routes->import($confDir.'/taxes/{routes}/*'.self::CONFIG_EXTS, 'glob');
    }
}
