<?php

namespace KnpBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('my_knp');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('word_provider')->defaultNull()->end()
                ->booleanNode('unicorn')->defaultTrue()->info('Yes it was')->end()
                ->integerNode('min')->defaultValue(3)->end()
            ->end()
        ;

        return $treeBuilder;
    }
}