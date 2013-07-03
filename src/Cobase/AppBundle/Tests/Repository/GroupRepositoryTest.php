<?php

namespace Cobase\AppBundle\Tests\Repository;

use Cobase\AppBundle\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GroupRepositoryTest extends WebTestCase
{
    /**
     * @var \Cobase\AppBundle\Repository\GroupRepository
     */
    private $eventRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->eventRepository = $kernel->getContainer()
                                       ->get('doctrine.orm.entity_manager')
                                       ->getRepository('CobaseAppBundle:Group');
    }

    public function testThatRepositoryExists()
    {
        $this->assertEquals('Cobase\AppBundle\Repository\GroupRepository',
                            get_class($this->groupRepository));
    }
    
}
