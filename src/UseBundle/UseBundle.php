<?php

namespace UseBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use UseBundle\DependencyInjection\UseBundleExtension;

class UseBundle extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     * Remove sanity check
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new UseBundleExtension();
        }
        return $this->extension;
    }
}