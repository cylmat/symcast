<?php

namespace SecurityAuth;

use SecurityAuth\DependencyInjection\SecurityAuthExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SecurityAuth extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new SecurityAuthExtension();
        }
        return $this->extension;
    }
}