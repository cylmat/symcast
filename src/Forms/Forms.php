<?php

namespace Forms;

use Forms\DependencyInjection\FormsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Forms extends Bundle
{
    /**
     * Overridden to allow for the custom extension alias.
     * Remove sanity check
     */
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new FormsExtension();
        }
        return $this->extension;
    }
}