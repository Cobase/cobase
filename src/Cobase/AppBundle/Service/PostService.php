<?php

namespace Cobase\AppBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Group,
    Cobase\AppBundle\Entity\Post,
    Cobase\UserBundle\Entity\User;

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
     * @param EntityManager    $em
     * @param EntityRepository $repository
     * @param SecurityContext  $security
     */
    public function __construct(EntityManager $em, EntityRepository $repository, SecurityContext $security)
    {
        $this->em               = $em;
        $this->repository       = $repository;
        $this->security         = $security;
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
    public function savePost(Post $post, Group $group, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $post->setUser($user);
        $post->setGroup($group);

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
}
