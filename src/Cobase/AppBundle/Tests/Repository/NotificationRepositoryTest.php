<?php
namespace Cobase\AppBundle\Tests\Repository;

use Cobase\AppBundle\Entity\Group;
use Cobase\Component\Test\ServiceTestCase;

/**
 * @group repository
 * @group notification-repository1
 */
class NotificationRepositoryTest extends ServiceTestCase
{
    /**
     * @test
     */
    public function assertExpectedGroups()
    {
        $repository = $this->getContainer()->get('cobase_app.repository.notification');

        $postEvent = $this->getFixtureFactory()->get('AppBundle\Entity\PostEvent');
        $postEvent2 = $this->getFixtureFactory()->get('AppBundle\Entity\PostEvent');
        $postEvent3 = $this->getFixtureFactory()->get('AppBundle\Entity\PostEvent');

        $this->getEntityManager()->flush();

        $postEvents = $repository->getNewPosts(2);

        $this->assertCount(2, $postEvents);

        $this->assertEquals($postEvent->getId(), $postEvents[0]->getId());
        $this->assertEquals($postEvent2->getId(), $postEvents[1]->getId());
    }
}
