<?php

namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;

class PostTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testThatPostEntityHasCorrectClass()
    {
        $post = new Post();

        $this->assertEquals(
            'Cobase\AppBundle\Entity\Post', get_class($post)
        );
    }
    
    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testPostInit()
    {
        $post = new Post();
        
        $this->assertNotNull(
            $post->getCreated()
        );

        $this->assertNull(
            $post->getDeleted()
        );
        
        $this->assertNotNull(
            $post->getLikes()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testEntityId()
    {
        $post = new Post();

        $this->assertNull(
            $post->getId()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testSettingUser()
    {
        $post = new Post();
        $user = new User();

        $post->setUser($user);

        $this->assertEquals(
            'Cobase\UserBundle\Entity\User',
            get_class($post->getUser())
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testSettingContent()
    {
        $post = new Post();
        $user = new User();

        $post->setContent("This is a test content");

        $this->assertEquals(
            $post->getContent(),
            "This is a test content"
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testThatSettingCreatedWorks()
    {
        $post = new Post();

        $date = new \DateTime();

        $post->setCreated($date);

        $this->assertEquals(
            $date,
            $post->getCreated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testThatSettingUpdatedWorks()
    {
        $post = new Post();

        $date = new \DateTime();

        $post->setUpdated($date);

        $this->assertEquals(
            $date,
            $post->getUpdated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testSetCreatedValue()
    {
        $post = new Post();

        $this->assertNotNull(
            $post->getUpdated()
        );

        $this->assertNotNull(
            $post->getCreated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testSetUpdatedValue()
    {
        $post = new Post();

        sleep(1);

        $post->setUpdated(new \DateTime());

        $this->assertNotNull(
            $post->getUpdated()
        );

        $this->assertNotNull(
            $post->getCreated()
        );

        $this->assertNotEquals(
            $post->getCreated(),
            $post->getUpdated()
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testSettingGroupToAPost()
    {
        $post = new Post();

        $this->assertEquals(
            sizeof($post->getGroup()),
            0,
            "Initially there are no group in post"
        );
        
        $group = new Group();
        $group->setTitle("This is a test group");

        $post->setGroup($group);
        
        $this->assertEquals(
            $post->getGroup()->getTitle(),
            "This is a test group"
        );

        $this->assertEquals(
            sizeof($post->getGroup()),
            1,
            "There should be one group in a post"
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testLikeableTypeIsCorrect()
    {
        $post = new Post();

        $this->assertEquals(
            $post->getLikeableType(),
            'post'
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testLikeableIdIsCorrect()
    {
        $postMock = $this->getMockBuilder('Cobase\AppBundle\Entity\Post')
            ->disableOriginalConstructor()
            ->setMethods(array('getId'))
            ->getMock();

        $postMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(23));
        
        $this->assertEquals(
            $postMock->getLikeableId(),
            23
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function testGroupHasNoLikesInitially()
    {
        $post = new Post();

        $this->assertEquals(
            sizeof($post->getLikes()),
            0
        );
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function assertFeedTitleIsNotShortened()
    {
        $user = new User();
        $user->setName("John");

        $post = new Post();
        $post->setUser($user);
        $post->setMaxFeedTitleLength(42);
        $post->setContent('The hyperactive horse walked into a saloon');

        $expectedTitle = 'The hyperactive horse walked into a saloon (John)';

        $this->assertEquals($expectedTitle, $post->getFeedItemTitle());
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function assertFeedTitleIsShortenedProperly()
    {
        $user = new User();
        $user->setName("John");

        $post = new Post();
        $post->setUser($user);
        $post->setMaxFeedTitleLength(20);
        $post->setContent('The hyperactive horse walked into a saloon');

        $expectedTitle = 'The hyperactive... (John)';

        $this->assertEquals($expectedTitle, $post->getFeedItemTitle());
    }

    /**
     * @test
     *
     * @group entity
     * @group post-entity
     */
    public function assertFeedTitleIsShortenedProperlyWhenLastCharIsSpace()
    {
        $user = new User();
        $user->setName("John");

        $post = new Post();
        $post->setUser($user);
        $post->setMaxFeedTitleLength(21);
        $post->setContent('The hyperactive horse walked into a saloon');

        $expectedTitle = 'The hyperactive horse... (John)';

        $this->assertEquals($expectedTitle, $post->getFeedItemTitle());
    }

    /*
    Tests yet to be implemented:
    - public function setLikes($likes)
    - public function getLikes()
    - public function removeLikes()
    */
}