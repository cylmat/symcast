<?php

namespace KnpBundle\DependencyInjection;

use KnpBundle\KnpProviderInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class KnpExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        // Pass arguments to service
        $definition = $container->getDefinition('knp.ipsum');

        /**
         * Solution with reference class
         */
        /*if (null !== $config['word_provider']) {
            $service_ref = new Reference($config['word_provider']);
            $definition->setArgument(0, $service_ref);
        }*/

        /**
         * Solution with alias and servicex.xml configuration
         */
        if (null !== $config['word_provider']) {
           $container->setAlias('knp.custom_provider', $config['word_provider']);
        }

        /**
         * Solution 3: work with CompilerPass and tags
         */
        //---
        // for autoregister tags
        $container->registerForAutoconfiguration(KnpProviderInterface::class)
            ->addTag('knp.provider.tag');

        // other arguments
        $definition->setArgument(1, $config['unicorn']); //second arg
        $definition->setArgument(2, $config['min']); //third arg
    }

    public function getAlias()
    {
        return 'my_knp';
    }
}