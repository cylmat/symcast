<?php

namespace KnpBundle;

interface KnpProviderInterface
{
    /**
     * Return an array of words to use for the fake text.
     */
    public function getWords(): array;
}