<?php
namespace Cobase\UserBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Like;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;
use PHPUnit_Framework_TestCase;

class UserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     *
     * @group user-entity
     */
    public function likesPost()
    {
        $user = new User();

        $post = new Post();
        $post2 = new Post();
        $book = new Book();

        $like = new Like($post);
        $like2 = new Like($post2);
        $like3 = new Like($book);

        $user->addLike($like)
            ->addLike($like2)
            ->addLike($like3);

        $this->assertTrue($user->likesPost($post));
    }
}
