<?php

namespace KnpBundle\Service;

use KnpBundle\KnpProviderInterface;

class KnpIpsum
{
    private $unicornsAreReal;
    private $minSunshine;

    /**
     * @ar KnpProviderInterface[]
     */
    private $providers;

    public function __construct(iterable $providers, bool $unicornsAreReal = true, $minSunshine = 3)
    {
        $this->unicornsAreReal = $unicornsAreReal;
        $this->minSunshine = $minSunshine;

        $this->providers = $providers;
    }

    /**
     * Returns several paragraphs of random ipsum text.
     *
     * @param int $count
     * @return string
     */
    public function getParagraphs(int $count = 3): string
    {
        $words = $this->getWords();

        $paragraphs = array();
        for ($i = 0; $i < count($words); $i++) {
            $paragraphs[] = '<p>'.($this->unicornsAreReal ? '1' : '0').$words[$i].'</p>'; 
            //$this->addJoy($this->getSentences($this->gauss(5.8, 1.93)));
        }

        return implode("\n\n", $paragraphs);
    }

    private function getWords(): array
    {
        $words = [];
        foreach ($this->providers as $provider) {
            $words = array_merge($words, $provider->getWords());
        }

        if (count($words) < 1 ) {
            throw new \RuntimeException("Words list must contain at least 2 words.");
        }

        return $words;
    }

}
