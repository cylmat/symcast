<?php

namespace UseBundle\Service;

use KnpBundle\KnpProviderInterface;

class CustomWithTagProvider implements KnpProviderInterface
{
    public function getWords(): array
    {
        return [
            'provided-with-tags',
            'provided2-with-tags'
        ];
    }
}