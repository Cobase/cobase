<?php
namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Like;
use Cobase\AppBundle\Entity\Liking;
use Cobase\AppBundle\Entity\Post;
use Cobase\AppBundle\Repository\LikeRepository;
use Cobase\UserBundle\Entity\User;

use Doctrine\ORM\EntityManager;

use \Exception;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

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

        $like = new Like($post);

        $like->setUser($user);

        $this->em->getConnection()->beginTransaction();
        $this->em->persist($like);
        $this->em->flush();

        $this->em->getConnection()->commit();

        return $like;
    }

    /**
     * @param Post $post
     * @param User $user
     * @throws \Exception
     */
    public function unlikePost(Post $post, User $user)
    {
        if (!$user->likesPost($post)) {
            throw new Exception("You don't like this post");
        }

        $like = $this->likeRepository->getPostSpecificLikeByUser($post, $user);

        if (null === $like) {
            throw new ResourceNotFoundException('No like found for this post');
        }

        $this->em->getConnection()->beginTransaction();
        $this->em->remove($like);
        $this->em->flush();
        $this->em->getConnection()->commit();
    }
}
