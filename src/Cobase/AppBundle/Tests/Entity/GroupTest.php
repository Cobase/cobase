<?php

namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;

class GroupTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatGroupEntityHasCorrectClass()
    {
        $group = new Group();
        
        $this->assertEquals( 
            'Cobase\AppBundle\Entity\Group', get_class($group) 
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
        $group = new Group();

        $this->assertNull(
            $group->getId()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatShortUrlIsSet()
    {
        $group = new Group();
        
        $this->assertNotNull( 
            $group->getShortUrl() 
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatShortUrlIsDifferent()
    {
        $group = new Group();

        $group->createShortUrl();
        $url1 = $group->getShortUrl();

        sleep(1);
        
        $group->createShortUrl();
        $url2 = $group->getShortUrl();
        
        $this->assertNotEquals(
            $url1,
            $url2
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatInitialAmountOfPostsIsZero()
    {
        $group = new Group();
        
        $this->assertEquals( 
            sizeof($group->getPosts()), 0 
        );
        
        $this->assertEquals( 
            $group->getCountPosts(), 0 
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
        $group = new Group();
        
        $this->assertTrue( 
            $group->getCreated() instanceof \DateTime 
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatUpdatedDateIsOfCorrectType()
    {
        $group = new Group();
        
        $this->assertTrue( 
            $group->getUpdated() instanceof \DateTime 
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatInitialStateIsPublic()
    {
        $group = new Group();
        
        $this->assertTrue( 
            $group->getIsPublic() 
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testChangingStateToNonPublic()
    {
        $group = new Group();

        $group->setIsPublic(false);

        $this->assertFalse(
            $group->getIsPublic()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSettingTags()
    {
        $group = new Group();

        $group->setTags("tag, tag2, tag3");
        
        $this->assertEquals(
            $group->getTags(),
            "tag, tag2, tag3"
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testInitialUserIsNotSet()
    {
        $group = new Group();
     
        $this->assertNull(
            $group->getUser()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSettingUser()
    {
        $group = new Group();
        $user = new User();
        
        $group->setUser($user);

        $this->assertEquals(
            'Cobase\UserBundle\Entity\User',
            get_class($group->getUser())
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatSettingCreatedWorks()
    {
        $group = new Group();

        $date = new \DateTime();

        $group->setCreated($date);

        $this->assertEquals(
            $date,
            $group->getCreated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatSettingUpdatedWorks()
    {
        $group = new Group();
        
        $date = new \DateTime();
        
        $group->setUpdated($date);
        
        $this->assertEquals(
            $date,
            $group->getUpdated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSetCreatedValue()
    {
        $group = new Group();

        $this->assertNotNull(
            $group->getUpdated()
        );

        $this->assertNotNull(
            $group->getCreated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSetUpdatedValue()
    {
        $group = new Group();

        sleep(1);
        
        $group->setUpdated(new \DateTime());
        
        $this->assertNotNull(
            $group->getUpdated()
        );

        $this->assertNotNull(
            $group->getCreated()
        );
        
        $this->assertNotEquals(
            $group->getCreated(),
            $group->getUpdated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatSettingTitleWorks()
    {
        $group = new Group();

        $title = 'This is a test';

        $group->setTitle($title);

        $this->assertEquals(
            $title,
            $group->getTitle()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testEchoEntity()
    {
        $group = new Group();

        $title = 'This is a test';

        $group->setTitle($title);
        
        $stringified = (string) $group;

        $this->assertEquals(
            $title,
            $stringified
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSetSlug()
    {
        $group = new Group();

        $title = 'This is a test';

        $group->setSlug($title);

        $this->assertEquals(
            'this-is-a-test',
            $group->getSlug()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testThatSlugifyWorks()
    {
        $group = new Group();

        $title = 'This is a test';

        $response = $group->slugify($title);

        $this->assertEquals(
            'this-is-a-test',
            $response
        );

        $title = '';

        $response = $group->slugify($title);

        $this->assertEquals(
            'n-a',
            $response
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group group-entity
     */
    public function testSettingDescription()
    {
        $group = new Group();

        $group->setDescription("This is a description");

        $this->assertEquals(
            'This is a description',
            $group->getDescription()
        );

        $group->setDescription("This is a description", 7);
        
        $this->assertEquals(
            'This is',
            $group->getDescription(7)
        );
    }

}