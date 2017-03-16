<?php

namespace PlatformClientBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;

class PlatformClientExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $servicesLoader = new YamlFileLoader($container,
                new FileLocator(__DIR__ . '/../Resources/config')
                );
        $servicesLoader->load('services.yml');
        
        $parametersLoader = new YamlFileLoader($container,
                new FileLocator(__DIR__ . '/../Resources/config')
                );
        $parametersLoader->load('parameters.yml');
        
        
        $routesLoader = new YamlFileLoader($container,
                new FileLocator(__DIR__ . '/../Resources/config')
                );
        $routesLoader->load('api_routes.yml');
        
        
    }
    
   
    
}


?>