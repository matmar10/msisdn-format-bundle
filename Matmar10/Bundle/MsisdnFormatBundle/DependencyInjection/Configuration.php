<?php

namespace Matmar10\Bundle\MsisdnFormatBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $defaultConfigFilename = dirname(__FILE__) . '/../Resources/config/msisdn-country-formats.xml';

        $treeBuilder->root('matmar10_msisdn_format')
            ->children()
                ->scalarNode('formats_filename')
                    ->cannotBeEmpty()
                    ->defaultValue($defaultConfigFilename)
                ->end()
            ->end();
        return $treeBuilder;
    }
}
