<?php
namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Like;
use PHPUnit_Framework_TestCase;

class LikeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @group entity
     * @group like-entity
     */
    public function likedAtIsSet()
    {
        $like = new Like();

        $this->assertInstanceOf('DateTime', $like->getLikedAt());
    }
}
