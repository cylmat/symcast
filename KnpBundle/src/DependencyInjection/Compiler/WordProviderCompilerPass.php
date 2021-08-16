<?php

namespace KnpBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class WordProviderCompilerPass implements CompilerPassInterface
{
    // One of the most common use-cases of compiler passes is to work with tagged services.
    public function process(ContainerBuilder $container)
    {
        // Like in Extension
        $definition = $container->getDefinition('knp.ipsum');
        $references = [];

        foreach ($container->findTaggedServiceIds('knp.provider.tag') as $id => $tags) {
            $references[] = new Reference($id);
        }

        $definition->setArgument(0, $references);
    }
}