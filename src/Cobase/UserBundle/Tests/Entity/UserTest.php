<?php
namespace Cobase\UserBundle\Tests\Entity;

use Cobase\AppBundle\Entity\Like;
use Cobase\AppBundle\Entity\Liking;
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

        $like = new Like();
        $like2 = new Like();
        $like3 = new Like();

        $post = new Post();

        $book = new Book();
        $book2 = new Book();

        $postLiking = new Liking($like, $post);
        $bookLiking = new Liking($like2, $book);
        $book2Liking = new Liking($like3, $book2);

        $user->addLike($like)
            ->addLike($like2)
            ->addLike($like3);


        $this->assertTrue($user->likesPost($post));
    }

    /**
     * @test
     *
     * @group user-entity
     */
    public function doesNotLikePost()
    {
        $user = new User();

        $like = new Like();
        $like2 = new Like();
        $like3 = new Like();

        $post = new Post();

        $book = new Book();
        $book2 = new Book();

        $postLiking = new Liking($like, $post);

        $bookLiking = new Liking($like2, $book);
        $book2Liking = new Liking($like3, $book2);

        $user->addLike($like2)
            ->addLike($like3);

        $this->assertFalse($user->likesPost($post));
    }
}
