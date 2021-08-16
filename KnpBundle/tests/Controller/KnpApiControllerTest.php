<?php

namespace KnpBundle\Tests\Controller;

use KnpBundle\KnpBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class KnpControllerTestKernel extends Kernel
{
    use MicroKernelTrait;

    public function __construct()
    {
        parent::__construct('test', true);
    }

    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // Sym5.1: $routes->import(__DIR__.'/../../src/Resources/config/routes.xml')->prefix('/api');
        $routes->import(__DIR__.'/../../src/Resources/config/routes.xml', '/flash/');
    }

    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        // needed ?
        $c->loadFromExtension('framework', [
            'secret' => 'F00',
        ]);
    }

    public function getCacheDir()
    {
        return __DIR__.'/../cache/'.spl_object_hash($this);
    }

    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new KnpBundle()
        ];   
    }

    // Do not implements. Implemented in MicroKernel
    // function registerContainerConfiguration(LoaderInterface $loader)
}

class KnpApiControllerTest extends TestCase
{
    // composer require symfony/browser-kit --dev
    public function testIndex()
    {
        $kernel = new KnpControllerTestKernel();
        $kernel->boot();

        $client = new KernelBrowser($kernel); // Sym4.3
        $client->request('GET', '/flash/');

        //var_dump($client->getResponse()->getContent());
        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}