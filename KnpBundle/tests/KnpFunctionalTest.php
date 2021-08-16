<?php

namespace KnpBundle\Tests;

use KnpBundle\KnpBundle;
use KnpBundle\KnpProviderInterface;
use KnpBundle\Service\KnpIpsum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;

class KnpTestKernel extends Kernel
{
    private $config;

    public function __construct(array $config=[])
    {
        parent::__construct('test', true); 

        $this->config = $config;
    }

    public function getCacheDir()
    {
        return __DIR__.'/cache/'.spl_object_hash($this);
    }

    public function registerBundles()
    {
        return [
            new KnpBundle()
        ];   
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(function(ContainerBuilder $container) {
            $container->register('test_stub_word_list', StubWords::class)
                ->addTag('knpu_ipsum_word_provider'); //added with compiler pass
            $container->loadFromExtension('my_knp', $this->config);
        });
    }
}

class StubWords implements KnpProviderInterface
{
    public function getWords(): array
    {
        return ['stub'];
    }
}

class KnpFunctionalTest extends TestCase
{
    public function testAutowiring()
    {
        $kernel = new KnpTestKernel();
        $kernel->boot();
        $container = $kernel->getContainer();

        $ipsum = $container->get('knp.ipsum');
        $this->assertInstanceOf(KnpIpsum::class, $ipsum);
    }

    public function  testServiceWiringWithConfiguration()
    {
        $kernel = new KnpTestKernel([
            'word_provider' => 'test_stub_word_list' // removed because use of tags now
        ]);

        $kernel->boot();
        $container = $kernel->getContainer();
        $ipsum = $container->get('knp.ipsum');

        //$this->assertStringContainsString('stub', $ipsum->getParagraphs(1));
    }

    public function tearDown(): void
    {
    }
}