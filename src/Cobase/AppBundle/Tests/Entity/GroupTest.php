<?php

namespace Cobase\AppBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;

class GroupTest extends \PHPUnit_Framework_TestCase
{
    public function testThatGroupEntityHasCorrectClass()
    {
        $group = new Group();
        
        $this->assertEquals( 
            'Cobase\AppBundle\Entity\Group', get_class($group) 
        );
    }

    public function testEntityId()
    {
        $group = new Group();

        $this->assertNull(
            $group->getId()
        );
    }

    public function testThatShortUrlIsSet()
    {
        $group = new Group();
        
        $this->assertNotNull( 
            $group->getShortUrl() 
        );
    }
    
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

    public function testThatCreatedDateIsOfCorrectType()
    {
        $group = new Group();
        
        $this->assertTrue( 
            $group->getCreated() instanceof \DateTime 
        );
    }

    public function testThatUpdatedDateIsOfCorrectType()
    {
        $group = new Group();
        
        $this->assertTrue( 
            $group->getUpdated() instanceof \DateTime 
        );
    }

    public function testThatInitialStateIsPublic()
    {
        $group = new Group();
        
        $this->assertTrue( 
            $group->getIsPublic() 
        );
    }

    public function testChangingStateToNonPublic()
    {
        $group = new Group();

        $group->setIsPublic(false);

        $this->assertFalse(
            $group->getIsPublic()
        );
    }
    
    public function testSettingTags()
    {
        $group = new Group();

        $group->setTags("tag, tag2, tag3");
        
        $this->assertEquals(
            $group->getTags(),
            "tag, tag2, tag3"
        );
    }

    public function testInitialUserIsNotSet()
    {
        $group = new Group();
     
        $this->assertNull(
            $group->getUser()
        );
    }
    
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