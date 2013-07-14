<?php

namespace Lmh\Bundle\MsisdnBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $defaultConfigFilename = dirname(__FILE__) . '/../Resources/config/msisdn-country-formats.xml';

        $treeBuilder->root('lmh_msisdn')
            ->children()
                ->scalarNode('formats_filename')
                    ->cannotBeEmpty()
                    ->defaultValue($defaultConfigFilename)
                ->end()
            ->end();
        return $treeBuilder;
    }
}
