<?php

namespace Cobase\AppBundle\Service;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\EntityRepository,
    Symfony\Component\Security\Core\SecurityContext,
    Cobase\AppBundle\Entity\Event,
    Cobase\UserBundle\Entity\User;

class EventService
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EventRepository
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
    public function getEvents()
    {
        return $this->repository->findAll();
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
    public function getLatestPublicEvents($limit = null)
    {
        return $this->repository->getLatestPublicEvents($limit);
    }

    /**
     * @return array
     */
    public function getAllPublicEvents($limit = null, $orderBy, $order)
    {
        return $this->repository->getAllPublicEvents($limit, $orderBy, $order);
    }

    /**
     * @param  string $id
     * @return Event
     */
    public function getEventById($id)
    {
        return $this->repository->findOneBy(
            array(
                'shortUrl' => $id
            )
        );
    }

    /**
     * @return array
     */
    public function getEventsForCurrentUser()
    {
        return $this->repository->findAllForUser($this->security->getToken()->getUser());
    }

    /**
     * @return array
     */
    public function getEventsForUser($user)
    {
        return $this->repository->findAllForUser($user);
    }

    /**
     * @return boolean
     */
    public function isCurrentUserCreatorOfEvent(Event $event)
    {
        return $event->getUser() == $this->security->getToken()->getUser();
    }

    /**
     * @param  Event $event
     * @param  User  $user
     * @return Event
     */
    public function saveEvent(Event $event, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $event->setUser($user);
        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }

    /**
     * @param  Event $event
     * @param  User  $user
     * @return Event
     */
    public function modifyEvent(Event $event, User $user = null)
    {
        if (!$user) {
            $user = $this->security->getToken()->getUser();
        }

        $event->setUser($user);
        $this->em->persist($event);
        $this->em->flush();

        return $event;
    }

}
