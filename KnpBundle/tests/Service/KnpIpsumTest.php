<?php

namespace KnpBundle\Tests\Service;

use KnpBundle\KnpProvider;
use KnpBundle\Service\KnpIpsum;
use KnpBundle\Service\KnpUIpsum;
use PHPUnit\Framework\TestCase;

class KnpIpsumTest extends TestCase
{
    public function testGetParagraphs()
    {   
        $ipsum = new KnpIpsum([new KnpProvider()]);
        $words = $ipsum->getParagraphs();

        $this->assertCount(1, explode(' ', $words));
    }
}