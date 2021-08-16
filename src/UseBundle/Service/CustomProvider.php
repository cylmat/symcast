<?php

namespace UseBundle\Service;

use KnpBundle\KnpProvider;
use KnpBundle\KnpProviderInterface;

class CustomProvider implements KnpProviderInterface
{
    // Not used directly
    // Replaced by autoconfigured tags and compiler pass
    public function getWords(): array
    {
        $words = (new KnpProvider)->getWords();
        $words[0] = 'alpine';

        return $words;
    }
}