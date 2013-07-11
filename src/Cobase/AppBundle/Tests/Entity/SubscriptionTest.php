<?php

namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Subscription;

class SubscriptionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatGroupEntityHasCorrectClass()
    {
        $subscription = new Subscription();

        $this->assertEquals(
            'Cobase\AppBundle\Entity\Subscription', get_class($subscription)
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testEntityId()
    {
        $subscription = new Subscription();

        $this->assertNull(
            $subscription->getId()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatCreatedDateIsOfCorrectType()
    {
        $subscription = new Subscription();

        $this->assertTrue(
            $subscription->getCreated() instanceof \DateTime
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatDeletedDateIsOfCorrectType()
    {
        $subscription = new Subscription();

        $subscription->setDeleted(new \DateTime());
        
        $this->assertTrue(
            $subscription->getDeleted() instanceof \DateTime
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatInitialDeletedStateIsNull()
    {
        $subscription = new Subscription();

        $this->assertNull(
            $subscription->getDeleted()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatSettingDeletedWorks()
    {
        $subscription = new Subscription();

        $subscription->setDeleted(new \DateTime());

        $this->assertNotNull(
            $subscription->getDeleted()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testInitialGroupIsNotSet()
    {
        $subscription = new Subscription();
        
        $this->assertNull(
            $subscription->getGroup()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSettingGroup()
    {
        $group = new Group();
        $subscription = new Subscription();

        $subscription->setGroup($group);

        $this->assertEquals(
            'Cobase\AppBundle\Entity\Group',
            get_class($subscription->getGroup())
        );
    }

}