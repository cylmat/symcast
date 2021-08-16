<?php

namespace KnpBundle;

use KnpBundle\DependencyInjection\Compiler\WordProviderCompilerPass;
use KnpBundle\DependencyInjection\KnpExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class KnpBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     * Remove sanity check
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new KnpExtension();
        }
        return $this->extension;
    }

    // Register the compiler pass
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new WordProviderCompilerPass());
    }
}
