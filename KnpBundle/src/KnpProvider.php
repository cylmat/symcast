<?php

namespace KnpBundle;

use KnpBundle\KnpProviderInterface;

class KnpProvider implements KnpProviderInterface
{
    public function getWords(): array
    {
        return [
            'alpha',
            'beta'
        ];
    }
}