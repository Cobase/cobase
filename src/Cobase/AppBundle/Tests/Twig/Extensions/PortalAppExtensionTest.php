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
        
        $this->assertEquals(true, method_exists($extension, 'showMaxLen'));
    }
    
    public function testShowMaxLenExtension()
    {
        $extension = new CobaseAppExtension();

        $this->assertEquals("abc", $extension->showMaxLen('abcdefg', 3));
        $this->assertEquals("abcdefg", $extension->showMaxLen('abcdefg', 7));
        $this->assertEquals("abcdefg", $extension->showMaxLen('abcdefg', 10));
    }
}