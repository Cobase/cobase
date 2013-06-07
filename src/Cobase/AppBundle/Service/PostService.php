<?php

namespace Cobase\AppBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Event,
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
    public function getLatestPostsForPublicEvents($limit = null)
    {
        return $this->repository->getLatestPostsForPublicEvents($limit);
    }

    /**
     * @return array
     */
    public function getAllPostsforPublicEvents($limit = null, $order)
    {
        return $this->repository->getAllPostsforPublicEvents($limit, $order);
    }

    /**
     * @param  int $id
     * @return Event
     */
    public function getPostById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Check to see if a user already has given a post for the group
     *
     * @param \Cobase\AppBundle\Entity\Event $event
     * @param \Cobase\UserBundle\Entity\User $user
     * @return bool
     */
    public function hasUserSubmittedPostForEvent(Event $event, User $user)
    {
        $result = $this->repository->findBy(
            array(
                'user'  => $user,
                'event' => $event
            )
        );

        return sizeOf($result) > 0 ? true : false;
    }

    /**
     * Save a post
     *
     * @param  Post $post
     * @param  Event $event
     * @param  User  $user
     * @return Event
     */
    public function savePost(Post $post, Event $event, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $post->setUser($user);
        $post->setEvent($event);

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
