<?php

namespace DoctrineQuery;

use DoctrineQuery\DependencyInjection\DoctrineQueryExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DoctrineQuery extends Bundle
{
    // MANDATORY method!
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new DoctrineQueryExtension();
        }
        return $this->extension;
    }
}