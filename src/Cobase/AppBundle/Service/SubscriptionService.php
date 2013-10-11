<?php

namespace Cobase\AppBundle\Service;

use Cobase\AppBundle\Entity\Notification;
use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Group,
    Cobase\AppBundle\Entity\Subscription,
    Cobase\UserBundle\Entity\User;

class SubscriptionService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var SubscriptionRepository
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
    public function getSubscriptions()
    {
        return $this->repository->findAll();
    }

    /**
     * @param  int $id
     * @return Subscription
     */
    public function getSubscriptionById($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Check to see if a user has subscribed to the group
     *
     * @param \Cobase\AppBundle\Entity\Group $group
     * @param \Cobase\UserBundle\Entity\User $user
     * @return bool
     */
    public function hasUserSubscribedToGroup(Group $group, User $user)
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
     * @param Group $group
     * @param User $user
     *
     * return $bool
     */
    public function isUserUserNotifiedOfNewGroupPosts(Group $group, User $user)
    {

    }

    /**
     * @param Group $group
     * @param User $user
     *
     * @return Notification
     */
    public function notifyUserOfNewGroupPosts(Group $group, User $user)
    {
        $notification = new Notification($user, $group);

        $this->em->persist($notification);
        $this->em->flush();

        return $notification;
    }

    /**
     * Subscribe to a group
     *
     * @param  Group $group
     * @param  User  $user
     * @return Group
     */
    public function subscribe(Group $group, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $subscription = new Subscription();

        $subscription->setUser($user);
        $subscription->setGroup($group);

        $this->em->persist($subscription);
        $this->em->flush();

        return $subscription;
    }

    /**
     * Unsubscribe from a group
     *
     * @param  Group $group
     * @param  User  $user
     * @return Group
     */
    public function unsubscribe(Group $group, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $entities = $this->repository->findBy(
            array(
                'user' => $user,
                'group' => $group
            )
        );

        foreach($entities as $entity) {
            $this->em->remove($entity);
        }

        $this->em->flush();
    }

    /**
     * Update subscription
     *
     * @param  Subscription $subscription
     */
    public function updateSubscription(Subscription $subscription)
    {
        $this->em->persist($subscription);
        $this->em->flush();

        return $subscription;
    }

    /**
     * Get all subscriptions for current user
     *
     * @return array
     */
    public function getSubscriptionsForUser($user)
    {
        return $this->repository->findAllForUser($user);
    }

    /**
     * Get all subscriptions for given group
     *
     * @return array
     */
    public function getSubscriptionsForGroup(Group $group)
    {
        return $this->repository->findAllForGroup($group);
    }

    /**
     * Get all posts related to groups user has subscribed to
     *
     * @param User $user
     * @return mixed
     */
    public function findAllSubscribedPostsForUser(User $user)
    {
        return $this->repository->findAllSubscribedPostsForUser($user);
    }
}
