<?php

namespace Cobase\AppBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Group,
    Cobase\UserBundle\Entity\User;

class GroupService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var GroupRepository
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
    public function getGroups()
    {
        return $this->repository->findBy(
            array(), 
            array(
                'title' => 'ASC'
            )
        );
    }

    /**
     * @return array
     */
    public function findAllBySearchWord($searchWord)
    {
        return $this->repository->findAllBySearchWord($searchWord);
    }

    /**
     * @return array
     */
    public function getLatestPublicGroups($limit = null)
    {
        return $this->repository->getLatestPublicGroups($limit);
    }

    /**
     * @return array
     */
    public function getAllPublicGroups($limit = null, $orderBy, $order)
    {
        return $this->repository->getAllPublicGroups($limit, $orderBy, $order);
    }

    /**
     * @param  string $id
     * @return Group
     */
    public function getGroupById($id)
    {
        return $this->repository->findOneBy(
            array(
                'id' => $id
            )
        );
    }

    /**
     * @return array
     */
    public function getGroupsForCurrentUser()
    {
        return $this->repository->findAllForUser($this->security->getToken()->getUser());
    }

    /**
     * @return array
     */
    public function getGroupsForUser($user)
    {
        return $this->repository->findAllForUser($user);
    }

    /**
     * @return boolean
     */
    public function isCurrentUserCreatorOfGroup(Group $group)
    {
        return $group->getUser() == $this->security->getToken()->getUser();
    }

    /**
     * @param  Group $group
     * @param  User  $user
     * @return Group
     */
    public function saveGroup(Group $group, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $group->setUser($user);
        $this->em->persist($group);
        $this->em->flush();

        return $group;
    }

    /**
     * @param  Group $group
     * @param  User  $user
     * @return Group
     */
    public function modifyGroup(Group $group, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $group->setUser($user);
        $this->em->persist($group);
        $this->em->flush();

        return $group;
    }

}
