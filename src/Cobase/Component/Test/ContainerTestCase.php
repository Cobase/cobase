<?php
namespace CoBase\Component\Test;

use Appkernel;

use PHPUnit_Framework_TestCase;

use Symfony\Component\DependencyInjection\Container;

class ContainerTestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @return Container
     */
    public function getContainer()
    {
        if (!$this->container) {
            $kernel = new AppKernel('test', true);
            $kernel->boot();

            $this->container = $kernel->getContainer();
        }

        return $this->container;
    }
}
