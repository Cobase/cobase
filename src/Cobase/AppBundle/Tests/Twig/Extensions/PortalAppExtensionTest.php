<?php

namespace Cobase\AppBundle\Tests\Twig\Extensions;

use Cobase\AppBundle\Twig\Extensions\CobaseAppExtension;

class CobaseAppExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testThatExtensionsExists()
    {
        $extension = new CobaseAppExtension();
        
        $this->assertEquals('Cobase\AppBundle\Twig\Extensions\CobaseAppExtension',
                            get_class($extension));
        
        $this->assertEquals(true, method_exists($extension, 'createdAgo'));
        
        $this->assertEquals(true, method_exists($extension, 'showMaxLen'));
    }
    
    public function testCreatedAgoExtension()
    {
        $extension = new CobaseAppExtension();

        $this->assertEquals("0 seconds ago", $extension->createdAgo(new \DateTime()));
        $this->assertEquals("34 seconds ago", $extension->createdAgo($this->getDateTime(-34)));
        $this->assertEquals("1 minute ago", $extension->createdAgo($this->getDateTime(-60)));
        $this->assertEquals("2 minutes ago", $extension->createdAgo($this->getDateTime(-120)));
        $this->assertEquals("1 hour ago", $extension->createdAgo($this->getDateTime(-3600)));
        $this->assertEquals("1 hour ago", $extension->createdAgo($this->getDateTime(-3601)));
        $this->assertEquals("2 hours ago", $extension->createdAgo($this->getDateTime(-7200)));
    }

    protected function getDateTime($delta)
    {
        return new \DateTime(date("Y-m-d H:i:s", time()+$delta));
    }
    
    public function testShowMaxLenExtension()
    {
        $extension = new CobaseAppExtension();

        $this->assertEquals("abc", $extension->showMaxLen('abcdefg', 3));
        $this->assertEquals("abcdefg", $extension->showMaxLen('abcdefg', 7));
        $this->assertEquals("abcdefg", $extension->showMaxLen('abcdefg', 10));
    }
}