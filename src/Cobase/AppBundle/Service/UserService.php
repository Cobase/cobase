<?php

namespace Cobase\AppBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Group,
    Cobase\AppBundle\Entity\Post,
    Cobase\UserBundle\Entity\User;

class UserService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var UserRepository
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
     * Return current user's entity or null if not logged in
     *
     * @return null|App/UserBundle/Entity/User
     */
    public function getCurrentUser() {
        $user = $this->security->getToken()->getUser();

        if ($user === 'anon.') {
            return null;
        }

        return $user;
    }

    /**
     * @return array
     */
    public function getUsers()
    {
        return $this->repository->findAll();
    }

    /**
     * @return array
     */
    public function getLatestUsers($limit = null)
    {
        return $this->repository->getLatestUsers($limit);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findAllGroupsByUser(User $user)
    {
        return $this->repository->findAllGroupsByUser($user);
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function findAllPostsByUser(User $user)
    {
        return $this->repository->findAllPostsByUser($user);
    }

    /**
     * @return array
     */
    public function getUserByUsername($username)
    {
        return $this->repository->findOneBy(
            array(
                'username' => $username,
            )
        );
    }

    /**
     * Filter out groups if group is non-public and user is not the owner of the group
     *
     * @param $userGroups
     * @return array
     */
    public function filterUnlistedGroups(Array $userGroups, User $user = null)
    {
        $groups = array();

        foreach($userGroups as $group) {
            if ($group instanceof Group) {
                if ($group->getIsPublic() === false) {
                    if ($group->getUser() == $user) {
                        $groups[] = $group;
                    }
                } else {
                    $groups[] = $group;
                }
            }
        }

        return $groups;
    }

    /**
     * Filter out posts of groups if the groups are non-public and user is not the owner of the group
     *
     * @param $userPosts
     * @return array
     */
    public function filterUnlistedGroupPosts(Array $userPosts, User $user = null)
    {
        $posts = array();

        foreach($userPosts as $post) {
            if ($post instanceof Post) {
                if ($post->getGroup()->getIsPublic() === false) {
                    if ($post->getGroup()->getUser() == $user) {
                        $posts[] = $post;
                    }
                } else {
                    $posts[] = $post;
                }
            }
        }

        return $posts;
    }

}
