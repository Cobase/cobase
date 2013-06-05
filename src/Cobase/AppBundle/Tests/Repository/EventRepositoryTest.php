<?php

namespace Cobase\AppBundle\Tests\Repository;

use Cobase\AppBundle\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EventRepositoryTest extends WebTestCase
{
    /**
     * @var \Cobase\AppBundle\Repository\EventRepository
     */
    private $eventRepository;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->eventRepository = $kernel->getContainer()
                                       ->get('doctrine.orm.entity_manager')
                                       ->getRepository('CobaseAppBundle:Event');
    }

    public function testThatRepositoryExists()
    {
        $this->assertEquals('Cobase\AppBundle\Repository\EventRepository',
                            get_class($this->eventRepository));
    }
    
    public function testGetTagWeights()
    {
        $tagsWeight = $this->eventRepository->getTagWeights(
            array('php', 'code', 'code', 'app', 'event')
        );

        $this->assertTrue(count($tagsWeight) > 1);

        // Test case where count is over max weight of 5
        $tagsWeight = $this->eventRepository->getTagWeights(
            array_fill(0, 10, 'php')
        );

        $this->assertTrue(count($tagsWeight) >= 1);

        // Test case with multiple counts over max weight of 5
        $tagsWeight = $this->eventRepository->getTagWeights(
            array_merge(array_fill(0, 10, 'php'), array_fill(0, 2, 'html'), array_fill(0, 6, 'js'))
        );

        $this->assertEquals(5, $tagsWeight['php']);
        $this->assertEquals(3, $tagsWeight['js']);
        $this->assertEquals(1, $tagsWeight['html']);

        // Test empty case
        $tagsWeight = $this->eventRepository->getTagWeights(array());

        $this->assertEmpty($tagsWeight);
    }

    public function testLatestEventsArePublic()
    {
        foreach ($this->eventRepository->getLatestPublicEvents(5) as $event) {
            $this->assertEquals('1', $event->getIsPublic());
        }
    }
}
