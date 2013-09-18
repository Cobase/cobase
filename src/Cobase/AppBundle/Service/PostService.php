<?php

namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Like;

use Cobase\AppBundle\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Security\Core\SecurityContext;
use Cobase\AppBundle\Entity\Group;
use Cobase\AppBundle\Entity\Post;
use Cobase\UserBundle\Entity\User;

use Exception;

class PostService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var PostRepository
     */
    protected $repository;

    /**
     * @var SecurityContext
     */
    protected $security;

    /**
     * @var LikeService
     */
    protected $likeService;

    /**
     * @param EntityManager     $em
     * @param PostRepository  $repository
     * @param SecurityContext   $security
     * @param LikeService       $likeService
     */
    public function __construct(
        EntityManager       $em,
        PostRepository      $repository,
        SecurityContext     $security,
        LikeService         $likeService)
    {
        $this->em               = $em;
        $this->repository       = $repository;
        $this->security         = $security;
        $this->likeService      = $likeService;
    }

    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->repository->findAll();
    }

    /**
     * @return array
     */

    /**
     * Get all latest posts for given user
     *
     * @param null $limit
     * @return mixed
     */
    public function getLatestPostsForPublicGroups($limit = null)
    {
        return $this->repository->getLatestPostsForPublicGroups($limit);
    }

    /**
     * @param Group $group
     * @param integer $limit
     * @return array
     */
    public function getLatestPublicPostsForGroup(Group $group, $limit = null)
    {
        return $this->repository->getLatestPublicPostsForGroup($group, $limit);
    }

    /**
     * @param Group $group
     * @return \Doctrine\ORM\Query
     */
    public function getLatestPublicPostsForGroupQuery(Group $group)
    {
        return $this->repository->getLatestPublicPostsForGroupQuery($group);
    }

    /**
     * @return array
     */
    public function getAllPostsforPublicGroups($limit = null, $order)
    {
        return $this->repository->getAllPostsforPublicGroups($limit, $order);
    }

    /**
     * @param  int $id
     * @return Post
     */
    public function getPostById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * @param  int $id
     * @return Post
     */
    public function getPostByGroupAndPostId(Group $group, $postId)
    {
        return $this->repository->findPostByGroupAndPostId($group, $postId);
    }

    /**
     * Check to see if a user already has given a post for the group
     *
     * @param \Cobase\AppBundle\Entity\Group $group
     * @param \Cobase\UserBundle\Entity\User $user
     * @return bool
     */
    public function hasUserSubmittedPostForGroup(Group $group, User $user)
    {
        $result = $this->repository->findBy(
            array(
                'user'  => $user,
                'group' => $group
            )
        );

        return sizeOf($result) > 0 ? true : false;
    }

    /**
     * Save a post
     *
     * @param  Post $post
     * @param  Group $group
     * @param  User  $user
     * @return Group
     */
    public function savePost(Post $post)
    {
        if (!$post->getUser()) {
            $post->setUser($this->security->getToken()->getUser());
        }

        $this->em->persist($post);
        $this->em->flush();

        return $post;
    }

    /**
     * Get all posts for current user
     *
     * @return array
     */
    public function getPostsForUser($user)
    {
        return $this->repository->findAllForUser($user);
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

        $like = $this->likeService->getPostSpecificLikeByUser($post, $user);

        if (null === $like) {
            throw new ResourceNotFoundException('No like found for this post');
        }

        $this->em->getConnection()->beginTransaction();
        $this->em->remove($like);
        $this->em->flush();
        $this->em->getConnection()->commit();
    }

    /**
     * @param Post $post
     *
     * @return integer
     */
    public function getLikeCount(Post $post)
    {
        return $this->repository->getLikeCount($post);
    }

    /**
     * @param Post $post
     *
     * return @array
     */
    public function getLikes(Post $post)
    {
        return $this->repository->getLikes($post);
    }

    public function fetchMetadataFromUrl($url)
    {
        $metadata = @get_meta_tags($url);

        if($metadata) {
            // adding title of the page in metadata
            $titleRegex = "/<title>(.+)<\/title>/i";
            preg_match_all($titleRegex, file_get_contents($url), $title, PREG_PATTERN_ORDER);
            $metadata['title'] = $title[1][0];

            // adding facebook metas in metadata
            $facebookRegex = "/<meta property='og:(.+)' content='(.+)'\/>/i";
            preg_match_all($facebookRegex, file_get_contents($url), $facebookMetas, PREG_PATTERN_ORDER);

            $metadata['facebook'] = array_combine($facebookMetas[1], $facebookMetas[2]);

            return $metadata;
        } else {
            return false;
        }
    }
}
