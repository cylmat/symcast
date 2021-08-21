<?php

namespace Fundamental;

use Fundamental\DependencyInjection\FundamentalExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Fundamental extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     * Remove sanity check
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FundamentalExtension();
        }
        return $this->extension;
    }
}