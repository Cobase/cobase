<?php
namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Like;
use Cobase\AppBundle\Entity\Liking;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Repository\LikeRepository;
use Cobase\UserBundle\Entity\User;

use Doctrine\ORM\EntityManager;

use \Exception;

class LikeService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var LikeRepository
     */
    private $likeRepository;

    /**
     * @param EntityManager $em
     * @param LikeRepository $likeRepository
     */
    public function __construct(EntityManager $em, LikeRepository $likeRepository)
    {
        $this->em               = $em;
        $this->likeRepository   = $likeRepository;
    }

    /**
     * @param Post $post
     * @param User $user
     *
     * @return Like
     */
    public function likePost(Post $post, User $user)
    {
        if ($user->likesPost($post)) {
            throw new Exception('You already like this post');
        }

        $like = new Like();

        $like->setUser($user);

        $this->em->getConnection()->beginTransaction();

        $this->em->persist($like);

        $liking = new Liking($like, $post);

        $this->em->persist($liking);
        $this->em->flush();

        $this->em->getConnection()->commit();

        echo count($user->getPostLikes());

        return $like;
    }

    public function likesPost(Post $post, User $user)
    {

    }

    public function unlikePost()
    {

    }
}
