<?php

namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Event;
use Cobase\AppBundle\Entity\Highfive;
use Cobase\UserBundle\Entity\User;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function testThatEntityExists()
    {
        $event = new Event();
        $this->assertEquals('Cobase\AppBundle\Entity\Event',get_class($event));
    }
    
    public function testSlugify()
    {
        $event = new Event();

        $this->assertEquals('hello-world', $event->slugify('Hello World'));
        $this->assertEquals('a-day-with-symfony2', $event->slugify('A Day With Symfony2'));
        $this->assertEquals('hello-world', $event->slugify('Hello    world'));
        $this->assertEquals('entry', $event->slugify('entry '));
        $this->assertEquals('entry', $event->slugify(' entry'));
    }
    
    public function testSetSlug()
    {
        $event = new Event();

        $event->setSlug('YouHighFiveMe Event');
        $this->assertEquals('youhighfiveme-event', $event->getSlug());
    }

    public function testSetTitle()
    {
        $event = new Event();

        $event->setTitle('Hello World');
        $this->assertEquals('hello-world', $event->getSlug());
    }
    
    public function testSetUserEntityToEvent()
    {
        $event = new Event();
        
        $user = $this->createTestUser();
        
        $event->setUser($user);
        $this->assertEquals('Morgan Freeman', $event->getUser()->getName());
    }

    public function testEventsShortUrlAutomaticallySet()
    {
        $event = new Event();
        $this->assertNotNull($event->getShortUrl());
    }
    
    private function createTestUser()
    {
        $user = new User();
        $user->setUsername('dev1');
        $user->setUsernameCanonical('dev1');
        $user->setPlainPassword('dev1');
        $user->setName('Morgan Freeman');
        $user->setGravatar('dev1@developer.me');
        $user->setEmail('dev1@developer.me');
        $user->setEmailCanonical('dev1@developer.me');
        $user->setEnabled(true);
        $user->setLocked(false);
        $user->setExpired(false);
        $user->setCredentialsExpired(false);
        
        return $user;
    }
    
    
    
}