<?php

namespace HttpKernel;

use HttpKernel\DependencyInjection\HttpKernelExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HttpKernel extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     * Remove sanity check
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new HttpKernelExtension();
        }
        return $this->extension;
    }
}