<?php
namespace Cobase\AppBundle\Tests\Functional;

use Cobase\AppBundle\Entity\Post;
use Cobase\Component\Test\ServiceTestCase;
use Cobase\UserBundle\Entity\User;

/**
 * @group functional
 */
class DatabaseTest extends ServiceTestCase
{
    /**
     * @test
     *
     * @group cur
     */
    public function assertSqliteDbIsUsed()
    {
        $post = new Post();
        $post->setContent('TestContent');

        $this->getEntityManager()->persist($post);
        $this->getEntityManager()->flush();
    }
}
