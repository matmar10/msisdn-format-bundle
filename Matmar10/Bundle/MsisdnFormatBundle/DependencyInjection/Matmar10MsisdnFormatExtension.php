<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class Matmar10MsisdnFormatExtension extends Extension {

    public function load(array $configs, ContainerBuilder $container)
    {
        // $configuration = $this->getConfiguration($configs, $container);
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('matmar10_msisdn_format.formats_filename', $config['formats_filename']);

        // load the services now that configurations have been loaded
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function getAlias()
    {
        return 'matmar10_msisdn_format';
    }
}
