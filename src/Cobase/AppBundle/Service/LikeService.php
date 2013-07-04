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
    public function getPostSpecificLikeByUser(Post $post, User $user)
    {
        return $this->likeRepository->getPostSpecificLikeByUser($post, $user);
    }
}
