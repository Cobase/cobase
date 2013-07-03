<?php

namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;

class GroupTest extends \PHPUnit_Framework_TestCase
{
    public function testThatEntityExists()
    {
        $group = new Group();
        $this->assertEquals('Cobase\AppBundle\Entity\Group',get_class($group));
    }
}