<?php
namespace CoBase\Component\Test;

use AppKernel;

use PHPUnit_Framework_TestCase;

use Symfony\Component\DependencyInjection\Container;

require_once($_SERVER['KERNEL_DIR'] . "/AppKernel.php");

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
